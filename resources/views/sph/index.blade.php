<x-app-layout>
    @push('breadcrumbs')
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Kalkulasi SPH'],
        ]" />
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kalkulasi SPH</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <a href="{{ route('sph.create', ['tipe' => 'dalam_kota']) }}"
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200 transition">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Dalam Kota</h3>
                            <p class="text-sm text-gray-500">Kalkulasi SPH untuk proyek dalam kota</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('sph.create', ['tipe' => 'luar_kota']) }}"
                   class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition group">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-amber-100 flex items-center justify-center group-hover:bg-amber-200 transition">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2l2 4h4l-3 3 1 4-4-2-4 2 1-4-3-3h4l2-4zM2 19l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2M2 22l2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2 2-2 2 2" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">Luar Kota</h3>
                            <p class="text-sm text-gray-500">Kalkulasi SPH untuk proyek luar kota</p>
                        </div>
                    </div>
                </a>
            </div>

            @if ($calculations->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Riwayat Kalkulasi</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm whitespace-nowrap">
                            <thead>
                                <tr class="border-b text-left">
                                    <th class="pb-3 pr-3 font-medium">Pekerjaan</th>
                                    <th class="pb-3 pr-3 font-medium">Tipe</th>
                                    <th class="pb-3 pr-3 font-medium">Total Biaya</th>
                                    <th class="pb-3 pr-3 font-medium">Harga Penawaran</th>
                                    <th class="pb-3 pr-3 font-medium">Tanggal</th>
                                    <th class="pb-3 pr-3 font-medium">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($calculations as $calc)
                                <tr class="border-b last:border-0">
                                    <td class="py-3 pr-3">{{ $calc->task->title }}</td>
                                    <td class="py-3 pr-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $calc->tipe === 'dalam_kota' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                                            {{ $calc->tipe === 'dalam_kota' ? 'Dalam Kota' : 'Luar Kota' }}
                                        </span>
                                    </td>
                                    <td class="py-3 pr-3">Rp {{ number_format($calc->total_biaya, 0, ',', '.') }}</td>
                                    <td class="py-3 pr-3">Rp {{ number_format($calc->harga_penawaran, 0, ',', '.') }}</td>
                                    <td class="py-3 pr-3">{{ $calc->created_at->format('d/m/Y') }}</td>
                                    <td class="py-3 pr-3">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('sph.show', $calc) }}" class="text-indigo-600 hover:text-indigo-800 text-xs font-medium">Lihat</a>
                                            <a href="{{ route('sph.edit', $calc) }}" class="text-amber-600 hover:text-amber-800 text-xs font-medium">Edit</a>
                                            <form method="POST" action="{{ route('sph.destroy', $calc) }}" class="inline" onsubmit="return confirm('Hapus kalkulasi ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $calculations->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
