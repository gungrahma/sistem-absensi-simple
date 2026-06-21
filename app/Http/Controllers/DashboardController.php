<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.attendance.index');
        }

        $today = $user->todayAttendance();

        return view('dashboard', [
            'user'        => $user,
            'attendance'  => $today,
            'jam_masuk'   => $user->jam_masuk ?? config('absensi.jam_masuk_default', '08:00:00'),
        ]);
    }
}
