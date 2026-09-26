<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of system-wide activity logs for administrators.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user'])->latest();

        // 1. Search by User name or description
        if ($request->filled('search')) {
            $search = trim($request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // 2. Role filter (user, vendor, admin)
        if ($request->filled('role') && in_array(strtolower($request->input('role')), ['user', 'vendor', 'admin'])) {
            $role = strtolower($request->input('role'));
            $query->whereHas('user', function ($userQuery) use ($role) {
                $userQuery->where('role', $role);
            });
        }

        // 3. Activity / Action filter
        if ($request->filled('action')) {
            $query->where('action', $request->input('action'));
        }

        // 4. Date filter preset & custom range
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
        $activityLogs = $query->paginate(15)->withQueryString();

        // Available actions for the dropdown
        $availableActions = ActivityLogger::getAvailableActions();

        return view('admin.activity_logs.index', compact('activityLogs', 'availableActions'));
    }
}
