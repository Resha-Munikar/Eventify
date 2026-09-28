<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorInquiryController extends Controller
{
    /**
     * Display a listing of inquiries specifically directed to the authenticated vendor.
     */
    public function index(Request $request)
    {
        $vendorId = Auth::id();

        // Strict vendor isolation query
        $query = Inquiry::with(['user', 'event', 'venue'])
            ->where('vendor_id', $vendorId)
            ->latest();

        // 1. Search Filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('event', function ($eq) use ($search) {
                      $eq->where('event_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('venue', function ($venQ) use ($search) {
                      $venQ->where('venue_name', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Type Filter
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // 3. Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Server-side pagination
        $inquiries = $query->paginate(10)->withQueryString();

        // Metrics for this vendor only
        $totalCount = Inquiry::where('vendor_id', $vendorId)->count();
        $unreadCount = Inquiry::where('vendor_id', $vendorId)->where('status', 'unread')->count();
        $resolvedCount = Inquiry::where('vendor_id', $vendorId)->where('status', 'resolved')->count();
        $todayCount = Inquiry::where('vendor_id', $vendorId)->whereDate('created_at', Carbon::today())->count();

        return view('vendor.inquiries.index', compact(
            'inquiries',
            'totalCount',
            'unreadCount',
            'resolvedCount',
            'todayCount'
        ));
    }

    /**
     * View inquiry details with strict vendor authorization check.
     */
    public function show(Inquiry $inquiry)
    {
        // Enforce strict ownership
        if ((int) $inquiry->vendor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized. You can only view inquiries related to your events and venues.');
        }

        $inquiry->load(['user', 'event', 'venue']);

        // Auto mark as read upon viewing if unread
        if ($inquiry->status === 'unread') {
            $inquiry->status = 'read';
            $inquiry->save();
            ActivityLogger::log('inquiry_viewed', 'Vendor viewed inquiry #' . $inquiry->id, $inquiry, Auth::user());
        }

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'inquiry' => $inquiry,
                'formatted_date' => $inquiry->created_at->format('M d, Y h:i A'),
                'human_date' => $inquiry->created_at->diffForHumans(),
            ]);
        }

        return view('vendor.inquiries.show', compact('inquiry'));
    }

    /**
     * Update inquiry status for the vendor.
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        // Enforce strict ownership
        if ((int) $inquiry->vendor_id !== (int) Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => 'required|string|in:unread,read,resolved',
        ]);

        $oldStatus = $inquiry->status;
        $inquiry->status = $validated['status'];
        $inquiry->save();

        ActivityLogger::log(
            'inquiry_status_updated',
            'Vendor updated inquiry #' . $inquiry->id . ' status to ' . ucfirst($inquiry->status),
            $inquiry,
            Auth::user()
        );

        if ($request->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $inquiry->status,
                'message' => 'Status updated to ' . ucfirst($inquiry->status) . '.',
            ]);
        }

        return back()->with('success', 'Inquiry status updated to ' . ucfirst($inquiry->status) . '.');
    }
}
