<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        $year = now()->year;

        // Orders per month (current year)
        $ordersByMonth = array_fill(1, 12, 0);
        Order::whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($o) { return (int) $o->created_at->format('n'); })
            ->each(function ($group, $month) use (&$ordersByMonth) {
                $ordersByMonth[$month] = $group->count();
            });

        // Customers per month (current year)
        $customersByMonth = array_fill(1, 12, 0);
        Customer::whereYear('created_at', $year)
            ->get()
            ->groupBy(function ($c) { return (int) $c->created_at->format('n'); })
            ->each(function ($group, $month) use (&$customersByMonth) {
                $customersByMonth[$month] = $group->count();
            });

        // Customers by region (based on order shipping_address.state UF)
        $ufToRegion = [
            'AC'=>'N','AP'=>'N','AM'=>'N','PA'=>'N','RO'=>'N','RR'=>'N','TO'=>'N',
            'AL'=>'NE','BA'=>'NE','CE'=>'NE','MA'=>'NE','PB'=>'NE','PE'=>'NE','PI'=>'NE','RN'=>'NE','SE'=>'NE',
            'DF'=>'CO','GO'=>'CO','MT'=>'CO','MS'=>'CO',
            'ES'=>'SE','MG'=>'SE','RJ'=>'SE','SP'=>'SE',
            'PR'=>'S','RS'=>'S','SC'=>'S',
        ];
        $regions = ['N'=>0,'NE'=>0,'CO'=>0,'SE'=>0,'S'=>0];
        Order::select(['shipping_address'])
            ->get()
            ->each(function ($o) use (&$regions, $ufToRegion) {
                $state = is_array($o->shipping_address) ? ($o->shipping_address['state'] ?? null) : null;
                $state = $state ? strtoupper(substr($state, 0, 2)) : null;
                if ($state && isset($ufToRegion[$state])) {
                    $regions[$ufToRegion[$state]] += 1;
                }
            });

        $stats = [
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'recent_orders' => Order::with('customer')->latest()->take(5)->get(),
            'charts' => [
                'year' => $year,
                'ordersByMonth' => array_values($ordersByMonth),
                'customersByMonth' => array_values($customersByMonth),
                'customersByRegion' => $regions,
            ],
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
        ]);
    }
}
