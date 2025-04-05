<?php
namespace Betod\Livotec\Controllers\Revenue;

use Backend\Classes\Controller;
use Betod\Livotec\Models\Revenue;

class RevenueChart extends Controller
{
    public function chart()
    {
        $revenueData = Revenue::selectRaw('DATE(sale_date) as sale_day, SUM(total_revenue) as total')
            ->groupBy('sale_day')
            ->orderBy('sale_day', 'asc')
            ->pluck('total', 'sale_day')->toArray();
        \Log::error('data: ', $revenueData);
        if ($revenueData) {
            return response()->json($revenueData);
        } else {
            return response()->json(['message' => 'No data']);
        }
    }
}
