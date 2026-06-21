<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Absensi Hari Ini :  {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Halo,</p>
                    <p class="text-2xl font-semibold">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Jam masuk reguler: <span class="font-semibold text-gray-700 dark:text-gray-200">{{ \Carbon\Carbon::parse($jam_masuk)->format('H:i') }}</span>
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Status Absensi</h3>

                    @if (! $attendance)
                        <p class="mb-4 text-gray-600 dark:text-gray-400">Kamu belum clock in hari ini.</p>
                        <form method="POST" action="{{ route('attendance.clock-in') }}">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow">
                                Clock In
                            </button>
                        </form>
                    @elseif (! $attendance->jam_keluar)
                        <div class="space-y-2 mb-4">
                            <p>Clock in: <span class="font-semibold">{{ $attendance->jam_masuk }}</span></p>
                            <p>Status:
                                @if ($attendance->status_masuk === 'telat')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Telat {{ $attendance->telat_menit }} menit</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Tepat waktu</span>
                                @endif
                            </p>
                        </div>
                        <form method="POST" action="{{ route('attendance.clock-out') }}">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg shadow">
                                Clock Out 
                            </button>
                        </form>
                    @else
                        <div class="space-y-2 text-gray-700 dark:text-gray-300">
                            <p>✅ Clock in: <span class="font-semibold">{{ $attendance->jam_masuk }}</span>
                                @if ($attendance->status_masuk === 'telat')
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-red-100 text-red-800">Telat {{ $attendance->telat_menit }} menit</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">Tepat waktu</span>
                                @endif
                            </p>
                            <p>Clock out: <span class="font-semibold">{{ $attendance->jam_keluar }}</span></p>
                            <p>Durasi kerja: <span class="font-semibold">{{ $attendance->durasi_kerja }}</span></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">Absensi hari ini sudah lengkap. Sampai besok!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
