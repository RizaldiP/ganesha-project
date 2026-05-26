<x-app-layout>
    @push('breadcrumbs')
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Template Surat', 'url' => route('letter-templates.index')],
            ['label' => 'Buat Surat'],
        ]" />
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Surat: {{ $letterTemplate->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="mb-4 px-4 py-2 bg-red-100 border border-red-200 text-red-700 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('letter-templates.download', $letterTemplate) }}" class="space-y-6">
                        @csrf

                        <div class="mb-4 px-4 py-3 bg-indigo-50 border border-indigo-200 text-indigo-700 rounded-md text-sm">
                            <strong>{{ count($letterTemplate->placeholders) }} placeholder</strong> ditemukan. Isi nilai untuk setiap placeholder di bawah.
                        </div>

                        @foreach($letterTemplate->placeholders as $placeholder)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ Str::title(str_replace(['_', '-'], ' ', $placeholder)) }}
                                </label>
                                <input type="text" name="values[{{ $placeholder }}]" value="{{ old('values.' . $placeholder) }}" required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                       placeholder="Isi {{ $placeholder }}">
                                @error("values.{$placeholder}")
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach

                        <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Download Surat (DOCX)
                            </button>
                            <a href="{{ route('letter-templates.index') }}"
                               class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
