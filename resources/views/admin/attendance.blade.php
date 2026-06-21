<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Monitor Absensi Karyawan
            </h2>
            <form method="GET" action="{{ route('admin.attendance.show', ['date' => $tanggal]) }}" class="flex items-center gap-2">
                <input type="date" name="date" value="{{ $tanggal }}"
                    class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 text-sm">
                <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-md">Lihat</button>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                    <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Total Karyawan</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/30 p-4 rounded-lg shadow-sm">
                    <p class="text-xs text-green-700 dark:text-green-300 uppercase">Hadir</p>
                    <p class="text-2xl font-bold text-green-800 dark:text-green-200">{{ $stats['hadir'] }}</p>
                </div>
                <div class="bg-red-50 dark:bg-red-900/30 p-4 rounded-lg shadow-sm">
                    <p class="text-xs text-red-700 dark:text-red-300 uppercase">Telat</p>
                    <p class="text-2xl font-bold text-red-800 dark:text-red-200">{{ $stats['telat'] }}</p>
                </div>
                <div class="bg-yellow-50 dark:bg-yellow-900/30 p-4 rounded-lg shadow-sm">
                    <p class="text-xs text-yellow-700 dark:text-yellow-300 uppercase">Belum Absen</p>
                    <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200">{{ $stats['belum_absen'] }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">
                        Daftar Absensi &mdash; {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Nama</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jam Masuk Reguler</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Clock In</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Clock Out</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($karyawan as $user)
                                    @php $absen = $user->attendances->first(); @endphp
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $user->jam_masuk ? \Carbon\Carbon::parse($user->jam_masuk)->format('H:i') : '-' }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $absen?->jam_masuk ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $absen?->jam_keluar ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm">
                                            @if (! $absen || ! $absen->jam_masuk)
                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">Belum Absen</span>
                                            @elseif ($absen->status_masuk === 'telat')
                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Telat {{ $absen->telat_menit }}m</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Tepat Waktu</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-3 text-sm text-center text-gray-500">Belum ada karyawan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
