<?php

namespace App\Http\Controllers;

use App\Mail\VendorKycApprovedMail;
use App\Mail\VendorKycRejectedMail;
use App\Models\VendorKyc;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AdminKycController extends Controller
{
    /**
     * Display a listing of all vendor KYC requests.
     */
    public function index(Request $request)
    {
        $query = VendorKyc::with(['user', 'reviewer'])->latest();

        // 1. Search filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('pan_vat_number', 'like', "%{$search}%")
                  ->orWhere('document_type', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Status filter
        if ($request->filled('status') && $request->input('status') !== 'all') {
            $query->where('status', $request->input('status'));
        }

        // 3. Document type filter
        if ($request->filled('document_type') && $request->input('document_type') !== 'all') {
            $query->where('document_type', $request->input('document_type'));
        }

        // 4. Date filter
        $dateFilter = $request->input('date_filter', 'all');
        if ($dateFilter === 'today') {
            $query->whereDate('created_at', Carbon::today());
        } elseif ($dateFilter === 'yesterday') {
            $query->whereDate('created_at', Carbon::yesterday());
        } elseif ($dateFilter === 'last_7_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(7)->startOfDay());
        } elseif ($dateFilter === 'last_30_days') {
            $query->where('created_at', '>=', Carbon::now()->subDays(30)->startOfDay());
        } elseif ($dateFilter === 'custom' || $request->filled('from_date') || $request->filled('to_date')) {
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->input('from_date'));
            }
            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->input('to_date'));
            }
        }

        $kycs = $query->paginate(10)->withQueryString();

        // Metrics
        $totalCount = VendorKyc::count();
        $pendingCount = VendorKyc::where('status', 'pending')->count();
        $approvedCount = VendorKyc::where('status', 'approved')->count();
        $rejectedCount = VendorKyc::where('status', 'rejected')->count();

        return view('admin.kyc.index', compact(
            'kycs',
            'totalCount',
            'pendingCount',
            'approvedCount',
            'rejectedCount'
        ));
    }

    /**
     * Show detailed KYC information (JSON for modal / details).
     */
    public function show(VendorKyc $kyc)
    {
        $kyc->load(['user', 'reviewer']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'kyc' => [
                    'id' => $kyc->id,
                    'user_id' => $kyc->user_id,
                    'vendor_name' => $kyc->user ? $kyc->user->name : 'N/A',
                    'vendor_email' => $kyc->user ? $kyc->user->email : 'N/A',
                    'business_name' => $kyc->business_name,
                    'pan_vat_number' => $kyc->pan_vat_number,
                    'document_type' => $kyc->document_type,
                    'document_front' => $kyc->document_front,
                    'document_front_url' => $kyc->document_front_url,
                    'document_front_is_pdf' => VendorKyc::isPdf($kyc->document_front),
                    'document_back' => $kyc->document_back,
                    'document_back_url' => $kyc->document_back_url,
                    'document_back_is_pdf' => VendorKyc::isPdf($kyc->document_back),
                    'company_registration_doc' => $kyc->company_registration_doc,
                    'company_registration_doc_url' => $kyc->company_registration_doc_url,
                    'company_registration_is_pdf' => VendorKyc::isPdf($kyc->company_registration_doc),
                    'status' => $kyc->status,
                    'rejection_reason' => $kyc->rejection_reason,
                    'approved_at' => $kyc->approved_at ? $kyc->approved_at->format('M d, Y h:i A') : null,
                    'rejected_at' => $kyc->rejected_at ? $kyc->rejected_at->format('M d, Y h:i A') : null,
                    'reviewer_name' => $kyc->reviewer ? $kyc->reviewer->name : null,
                    'created_at' => $kyc->created_at->format('M d, Y h:i A'),
                    'human_created_at' => $kyc->created_at->diffForHumans(),
                ],
            ]);
        }

        return redirect()->route('admin.kyc.index');
    }

    /**
     * Approve a vendor KYC request.
     */
    public function approve(Request $request, VendorKyc $kyc)
    {
        $kyc->status = 'approved';
        $kyc->approved_at = Carbon::now();
        $kyc->rejected_at = null;
        $kyc->rejection_reason = null;
        $kyc->reviewed_by = Auth::id();
        $kyc->save();

        ActivityLogger::log(
            'kyc_approved',
            'Approved KYC verification for "' . $kyc->business_name . '" (Vendor: ' . ($kyc->user->name ?? 'User #' . $kyc->user_id) . ')',
            $kyc,
            Auth::user()
        );

        // Send Email Notification
        if ($kyc->user && $kyc->user->email) {
            try {
                Mail::to($kyc->user->email)->send(new VendorKycApprovedMail($kyc->user, $kyc));
            } catch (\Throwable $e) {
                Log::error('Failed to send KYC approval email to ' . $kyc->user->email . ': ' . $e->getMessage());
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor KYC has been approved successfully.',
                'kyc' => $kyc,
            ]);
        }

        return back()->with('success', 'Vendor KYC has been approved successfully.');
    }

    /**
     * Reject a vendor KYC request with feedback reason.
     */
    public function reject(Request $request, VendorKyc $kyc)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:5|max:1000',
        ], [
            'rejection_reason.required' => 'Please provide a reason or feedback for rejecting this KYC request.',
            'rejection_reason.min' => 'Rejection reason must be at least 5 characters.',
        ]);

        $reason = $validated['rejection_reason'];

        $kyc->status = 'rejected';
        $kyc->rejected_at = Carbon::now();
        $kyc->approved_at = null;
        $kyc->rejection_reason = $reason;
        $kyc->reviewed_by = Auth::id();
        $kyc->save();

        ActivityLogger::log(
            'kyc_rejected',
            'Rejected KYC verification for "' . $kyc->business_name . '". Reason: ' . $reason,
            $kyc,
            Auth::user()
        );

        // Send Email Notification
        if ($kyc->user && $kyc->user->email) {
            try {
                Mail::to($kyc->user->email)->send(new VendorKycRejectedMail($kyc->user, $kyc, $reason));
            } catch (\Throwable $e) {
                Log::error('Failed to send KYC rejection email to ' . $kyc->user->email . ': ' . $e->getMessage());
            }
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Vendor KYC has been rejected. Feedback has been sent to the vendor.',
                'kyc' => $kyc,
            ]);
        }

        return back()->with('success', 'Vendor KYC has been rejected. Feedback has been sent to the vendor.');
    }
}
