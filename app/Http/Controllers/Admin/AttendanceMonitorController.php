<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceMonitorController extends Controller
{
    public function index(Request $request)
    {
        return $this->showByDate($request, Carbon::today()->toDateString());
    }

    public function showByDate(Request $request, string $date)
    {
        $tanggal = Carbon::parse($date)->toDateString();

        $karyawan = User::where('role', 'karyawan')
            ->with(['attendances' => fn ($q) => $q->whereDate('tanggal', $tanggal)])
            ->orderBy('name')
            ->get();

        $stats = [
            'total'       => $karyawan->count(),
            'hadir'       => $karyawan->filter(fn ($u) => optional($u->attendances->first())->jam_masuk)->count(),
            'telat'       => $karyawan->filter(fn ($u) => optional($u->attendances->first())->status_masuk === 'telat')->count(),
            'belum_absen' => $karyawan->filter(fn ($u) => ! optional($u->attendances->first())->jam_masuk)->count(),
        ];

        return view('admin.attendance', [
            'karyawan' => $karyawan,
            'tanggal'  => $tanggal,
            'stats'    => $stats,
        ]);
    }
}
