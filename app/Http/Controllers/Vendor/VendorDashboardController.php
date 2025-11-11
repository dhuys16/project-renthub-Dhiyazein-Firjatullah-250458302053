<?php

namespace App\Http\Controllers\Vendor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorDashboardController extends Controller
{
    /**
     * Display the vendor dashboard.
     */
    public function index()
    {
        return view('vendors.dashboard');
    }
}
