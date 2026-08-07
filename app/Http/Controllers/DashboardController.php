<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();
        $weekStart = now()->startOfWeek();
        $weekEnd = now()->endOfWeek();
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $salesFor = static fn ($start, $end): array => [
            'quantity' => (int) Order::query()
                ->where('status', 'delivered')
                ->whereBetween('created_at', [$start, $end])
                ->sum('quantity'),
            'revenue' => (int) Order::query()
                ->where('status', 'delivered')
                ->whereBetween('created_at', [$start, $end])
                ->sum('total'),
        ];

        $statusCountsFor = static fn ($start, $end): array => Order::query()
            ->whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['pending', 'delivered', 'cancelled'])
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->map(fn ($count) => (int) $count)
            ->all();

        return view('dasgboard.pages.index', [
            'orders' => Order::where('status', '!=', 'fake')->latest()->paginate(15),
            'orderCount' => Order::where('status', '!=', 'fake')->count(),
            'pendingCount' => Order::where('status', 'pending')->count(),
            'totalSales' => Order::where('status', 'delivered')->sum('total'),
            'sales' => [
                'today' => $salesFor($todayStart, $todayEnd),
                'week' => $salesFor($weekStart, $weekEnd),
                'month' => $salesFor($monthStart, $monthEnd),
            ],
            'statusCounts' => [
                'today' => $statusCountsFor($todayStart, $todayEnd),
                'month' => $statusCountsFor($monthStart, $monthEnd),
            ],
        ]);
    }
}
