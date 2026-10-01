<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.partials.dashboard');
        }
        return view('dashboards.admin');
    }

    public function employee()
    {
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('employee.partials.dashboard');
        }
        return view('dashboards.employee');
    }

    public function staff()
    {
        if (request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('staff.partials.dashboard');
        }
        return view('dashboards.staff');
    }
}
