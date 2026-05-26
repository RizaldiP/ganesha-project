<x-app-layout>
    @push('breadcrumbs')
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Template Surat'],
        ]" />
    @endpush
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Template Surat</h2>
            <a href="{{ route('letter-templates.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 shadow-sm">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Upload Template
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-2 bg-green-100 border border-green-200 text-green-700 rounded-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-200 text-red-700 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Search --}}
            <div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('letter-templates.index') }}" class="flex items-center gap-3">
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   placeholder="Cari template surat...">
                        </div>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                            Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('letter-templates.index') }}"
                               class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                @if($templates->isEmpty() && request('search'))
                    <div class="p-6 text-center text-gray-400 text-sm">Tidak ditemukan template dengan kata kunci "<strong>{{ request('search') }}</strong>".</div>
                @elseif($templates->isEmpty())
                    <div class="p-6 text-center text-gray-400 text-sm">Belum ada template. Klik "Upload Template" untuk memulai.</div>
                @else
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Placeholder</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Upload</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($templates as $template)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $template->name }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($template->placeholders as $placeholder)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-700">[{{ $placeholder }}]</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-500">{{ $template->created_at->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                    <td class="px-4 py-3 text-right text-sm">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('letter-templates.generate', $template) }}"
                                               class="inline-flex items-center px-3 py-1.5 bg-indigo-600 text-white text-xs font-medium rounded-md hover:bg-indigo-700 transition">
                                                Buat Surat
                                            </a>
                                            <form method="POST" action="{{ route('letter-templates.destroy', $template) }}"
                                                  onsubmit="return confirm('Hapus template ini?');">
                                                @csrf @method('DELETE')
                                                <button class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white text-xs font-medium rounded-md hover:bg-red-700 transition">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="p-4 border-t border-gray-100">
                        {{ $templates->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
