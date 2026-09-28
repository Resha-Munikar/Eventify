<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\User;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    /**
     * Display a listing of all inquiries and contact messages.
     */
    public function index(Request $request)
    {
        $query = Inquiry::with(['user', 'vendor', 'event', 'venue'])->latest();

        // 1. Search filter
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function ($vq) use ($search) {
                      $vq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('event', function ($eq) use ($search) {
                      $eq->where('event_name', 'like', "%{$search}%")
                         ->orWhere('venue', 'like', "%{$search}%");
                  })
                  ->orWhereHas('venue', function ($venQ) use ($search) {
                      $venQ->where('venue_name', 'like', "%{$search}%")
                           ->orWhere('location', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Type filter
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // 3. Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // 4. Vendor filter
        if ($request->filled('vendor_id')) {
            $query->where('vendor_id', $request->input('vendor_id'));
        }

        // 5. Date filter
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

        // Server-side pagination
        $inquiries = $query->paginate(10)->withQueryString();

        // Metrics
        $totalCount = Inquiry::count();
        $unreadCount = Inquiry::where('status', 'unread')->count();
        $resolvedCount = Inquiry::where('status', 'resolved')->count();
        $todayCount = Inquiry::whereDate('created_at', Carbon::today())->count();

        // Vendors list for filtering
        $vendors = User::where('role', 'vendor')->orderBy('name')->get(['id', 'name', 'email']);

        return view('admin.inquiries.index', compact(
            'inquiries',
            'totalCount',
            'unreadCount',
            'resolvedCount',
            'todayCount',
            'vendors'
        ));
    }

    /**
     * Show inquiry details (JSON for modal / details).
     */
    public function show(Inquiry $inquiry)
    {
        $inquiry->load(['user', 'vendor', 'event', 'venue']);

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'inquiry' => $inquiry,
                'formatted_date' => $inquiry->created_at->format('M d, Y h:i A'),
                'human_date' => $inquiry->created_at->diffForHumans(),
            ]);
        }

        return view('admin.inquiries.show', compact('inquiry'));
    }

    /**
     * Update inquiry status.
     */
    public function updateStatus(Request $request, Inquiry $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:unread,read,resolved',
        ]);

        $oldStatus = $inquiry->status;
        $inquiry->status = $validated['status'];
        $inquiry->save();

        ActivityLogger::log(
            'inquiry_status_updated',
            'Updated inquiry #' . $inquiry->id . ' status from ' . ucfirst($oldStatus) . ' to ' . ucfirst($inquiry->status),
            $inquiry
        );

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $inquiry->status,
                'message' => 'Inquiry status updated to ' . ucfirst($inquiry->status) . '.',
            ]);
        }

        return back()->with('success', 'Inquiry status updated to ' . ucfirst($inquiry->status) . '.');
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(Inquiry $inquiry)
    {
        $id = $inquiry->id;
        $inquiry->delete();

        ActivityLogger::log('inquiry_status_updated', 'Deleted contact inquiry #' . $id);

        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry #' . $id . ' deleted successfully.');
    }
}
