<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $user = Auth::user();

        $existing = $user->todayAttendance();
        if ($existing && $existing->jam_masuk) {
            return back()->with('error', 'Kamu sudah clock in hari ini.');
        }

        $jamMasukDefault = config('absensi.jam_masuk_default', '08:00:00');
        $jamMasukUser    = $user->jam_masuk ?? $jamMasukDefault;

        $now           = Carbon::now();
        $batasMasuk    = Carbon::parse($now->toDateString() . ' ' . $jamMasukUser);
        $telatMenit    = $now->greaterThan($batasMasuk)
            ? (int) round($now->diffInMinutes($batasMasuk, true))
            : 0;
        $statusMasuk   = $telatMenit > 0 ? 'telat' : 'tepat_waktu';

        Attendance::updateOrCreate(
            ['user_id' => $user->id, 'tanggal' => $now->toDateString()],
            [
                'jam_masuk'    => $now->format('H:i:s'),
                'status_masuk' => $statusMasuk,
                'telat_menit'  => $telatMenit,
            ]
        );

        return back()->with('success', 'Clock in berhasil. Status: ' . str_replace('_', ' ', $statusMasuk) .
            ($telatMenit > 0 ? " (telat {$telatMenit} menit)" : ''));
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();

        $attendance = $user->todayAttendance();
        if (! $attendance || ! $attendance->jam_masuk) {
            return back()->with('error', 'Kamu belum clock in hari ini.');
        }
        if ($attendance->jam_keluar) {
            return back()->with('error', 'Kamu sudah clock out hari ini.');
        }

        $now = Carbon::now();
        $attendance->update(['jam_keluar' => $now->format('H:i:s')]);

        return back()->with('success', 'Clock out berhasil. Durasi kerja: ' . ($attendance->durasi_kerja ?? '-'));
    }
}
