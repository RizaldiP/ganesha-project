<x-app-layout>
    @push('breadcrumbs')
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Template Surat', 'url' => route('letter-templates.index')],
            ['label' => 'Upload'],
        ]" />
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upload Template Surat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="p-6">
                    <form method="POST" action="{{ route('letter-templates.store') }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                   placeholder="Contoh: Surat Pernyataan Kerja">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">File DOCX</label>
                            <input type="file" name="file" accept=".docx" required
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <p class="text-xs text-gray-400 mt-1">Upload file .docx dengan placeholder seperti <code class="text-indigo-600">[nama]</code>, <code class="text-indigo-600">[tanggal]</code>, dll.</p>
                            @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 transition">
                                Upload & Deteksi Placeholder
                            </button>
                            <a href="{{ route('letter-templates.index') }}"
                               class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-300 transition">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Cara Membuat Template</h4>
                <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
                    <li>Buat file DOCX di Microsoft Word / WPS</li>
                    <li>Tulis teks surat, gunakan <code class="bg-blue-100 px-1 rounded">[nama_placeholder]</code> untuk bagian yang ingin diisi nanti</li>
                    <li>Upload file DOCX di sini</li>
                    <li>System akan otomatis mendeteksi placeholder dan membuat form input</li>
                    <li>Isi form, download surat yang sudah terisi</li>
                </ol>
            </div>
        </div>
    </div>
</x-app-layout>
