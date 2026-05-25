<x-app-layout>
    @push('breadcrumbs')
        @if (isset($sph))
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Kalkulasi SPH', 'url' => route('sph.index')],
            ['label' => 'Edit'],
        ]" />
        @else
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Kalkulasi SPH', 'url' => route('sph.index')],
            ['label' => $tipe === 'dalam_kota' ? 'Dalam Kota' : 'Luar Kota'],
        ]" />
        @endif
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($sph) ? 'Edit Kalkulasi SPH' : 'Kalkulasi SPH - ' . ($tipe === 'dalam_kota' ? 'Dalam Kota' : 'Luar Kota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ isset($sph) ? route('sph.update', $sph) : route('sph.store') }}" x-data="sphCalculator()" x-init="initForm({{ isset($sph) ? json_encode($sph->toArray()) : 'null' }})">
                    @csrf
                    @if (isset($sph)) @method('PATCH') @endif

                    <input type="hidden" name="tipe" value="{{ $tipe }}">

                    <div class="space-y-6">
                        <div class="border rounded-lg p-4 space-y-4">
                            <h3 class="font-semibold text-gray-800 text-sm border-b pb-2">Informasi SPH</h3>

                            <div>
                                <x-input-label for="task_id" value="Pekerjaan" />
                                <select id="task_id" name="task_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    <option value="">-- Pilih Pekerjaan --</option>
                                    @foreach ($tasks as $task)
                                    <option value="{{ $task->id }}" @selected(isset($sph) && $sph->task_id == $task->id)>{{ $task->title }} ({{ ucfirst($task->task_type) }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('task_id')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="nomor_sph" value="Nomor SPH" />
                                    <x-text-input id="nomor_sph" type="text" name="nomor_sph" x-model="form.nomor_sph" class="block w-full" />
                                    <x-input-error :messages="$errors->get('nomor_sph')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="tanggal_sph" value="Tanggal SPH" />
                                    <x-text-input id="tanggal_sph" type="date" name="tanggal_sph" x-model="form.tanggal_sph" class="block w-full" />
                                    <x-input-error :messages="$errors->get('tanggal_sph')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-lg p-4 space-y-4">
                            <h3 class="font-semibold text-gray-800 text-sm border-b pb-2">Biaya Langsung</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="jasa" value="Jasa" />
                                    <input id="jasa" type="text" x-model="honorariumDisplay" @input="updateField('honorarium', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="honorarium" x-model="form.honorarium">
                                    <x-input-error :messages="$errors->get('honorarium')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="material" value="Material" />
                                    <input id="material" type="text" x-model="materialDisplay" @input="updateField('material', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="material" x-model="form.material">
                                    <x-input-error :messages="$errors->get('material')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="transport" value="Transport/Hari" />
                                    <input id="transport" type="text" x-model="transportDisplay" @input="updateField('transport', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="transport" x-model="form.transport">
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-xs text-gray-400">Jumlah Minggu:</span>
                                        <input type="number" name="jumlah_minggu_transport" x-model="form.jumlah_minggu_transport" min="1" step="1" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <span class="text-xs text-gray-500 font-medium" x-text="'\u00D7 ' + jumlahTeknisi + ' teknisi'"></span>
                                        <span class="text-xs text-gray-500 font-medium" x-text="'= Rp ' + formatNumber(totalTransport)"></span>
                                    </div>
                                    <x-input-error :messages="$errors->get('transport')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="uang_harian" value="Uang Makan/Hari" />
                                    <input id="uang_harian" type="text" x-model="uang_harianDisplay" @input="updateField('uang_harian', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="uang_harian" x-model="form.uang_harian">
                                    <div class="mt-1 flex items-center gap-2">
                                        <span class="text-xs text-gray-400">Jumlah Minggu:</span>
                                        <input type="number" name="jumlah_minggu_harian" x-model="form.jumlah_minggu_harian" min="1" step="1" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <span class="text-xs text-gray-500 font-medium" x-text="'\u00D7 ' + jumlahTeknisi + ' teknisi'"></span>
                                        <span class="text-xs text-gray-500 font-medium" x-text="'= Rp ' + formatNumber(totalUangHarian)"></span>
                                    </div>
                                    <x-input-error :messages="$errors->get('uang_harian')" class="mt-2" />
                                </div>
                                @if ($tipe === 'luar_kota')
                                <div>
                                    <x-input-label for="akomodasi" value="Akomodasi" />
                                    <input id="akomodasi" type="text" x-model="akomodasiDisplay" @input="updateField('akomodasi', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="akomodasi" x-model="form.akomodasi">
                                    <x-input-error :messages="$errors->get('akomodasi')" class="mt-2" />
                                </div>
                                @endif
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="biaya_lain" value="Biaya Lain-lain" />
                                    <input id="biaya_lain" type="text" x-model="biaya_lainDisplay" @input="updateField('biaya_lain', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="biaya_lain" x-model="form.biaya_lain">
                                    <x-input-error :messages="$errors->get('biaya_lain')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="biaya_lain_keterangan" value="Keterangan Biaya Lain" />
                                    <x-text-input id="biaya_lain_keterangan" type="text" name="biaya_lain_keterangan" x-model="form.biaya_lain_keterangan" class="block w-full" />
                                    <x-input-error :messages="$errors->get('biaya_lain_keterangan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-lg p-4 space-y-4">
                            <h3 class="font-semibold text-gray-800 text-sm border-b pb-2">Upah Teknisi Assign</h3>
                            <p class="text-xs text-gray-400">Masukkan upah per bulan untuk setiap teknisi yang ditugaskan.</p>

                            <template x-for="(t, idx) in teknisiAssignments" :key="idx">
                                <div class="flex flex-wrap items-end gap-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1 min-w-[120px]">
                                        <label class="block text-xs font-medium text-gray-600 mb-1" x-text="'Teknisi ' + (idx + 1)"></label>
                                        <input type="text" x-model="t.nama" :name="'teknisi_assignments[' + idx + '][nama]'" placeholder="Nama teknisi" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Upah/Bulan</label>
                                        <input type="text" x-model="t.upahDisplay" @input="updateTeknisiUpah(idx, $event.target.value)" placeholder="Rp 0" class="block w-36 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                        <input type="hidden" :name="'teknisi_assignments[' + idx + '][upah_per_bulan]'" x-model="t.upah_per_bulan">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah Bulan</label>
                                        <input type="number" x-model="t.jumlah_bulan" :name="'teknisi_assignments[' + idx + '][jumlah_bulan]'" min="0" step="0.5" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                    </div>
                                    <div class="pb-1">
                                        <span class="text-xs text-gray-400">Subtotal:</span>
                                        <span class="text-sm font-medium text-gray-700 ml-1" x-text="'Rp ' + formatNumber(t.total || 0)"></span>
                                    </div>
                                    <button type="button" @click="removeTeknisi(idx)" x-show="teknisiAssignments.length > 1" class="pb-1 text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </template>

                            <button type="button" @click="addTeknisi()" class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Tambah Teknisi
                            </button>

                            <div x-show="teknisiAssignments.length > 0" class="p-3 bg-indigo-50 rounded-lg">
                                <span class="text-xs text-indigo-500">Total Upah Teknisi</span>
                                <p class="text-lg font-bold text-indigo-700" x-text="'Rp ' + formatNumber(totalTeknisiUpah)"></p>
                            </div>
                        </div>

                        <div class="border rounded-lg p-4 space-y-4">
                            <h3 class="font-semibold text-gray-800 text-sm border-b pb-2">Overhead & Keuntungan</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="overhead" value="Overhead" />
                                    <input id="overhead" type="text" x-model="overheadDisplay" @input="updateField('overhead', $event.target.value)" placeholder="Rp 0" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                                    <input type="hidden" name="overhead" x-model="form.overhead">
                                    <x-input-error :messages="$errors->get('overhead')" class="mt-2" />
                                </div>
                                <div>
                                    <x-input-label for="margin_keuntungan" value="Margin Keuntungan (%)" />
                                    <x-text-input id="margin_keuntungan" type="number" name="margin_keuntungan" x-model="form.margin_keuntungan" min="0" step="0.1" class="block w-full" />
                                    <x-input-error :messages="$errors->get('margin_keuntungan')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <div class="border rounded-lg p-4 space-y-4">
                            <h3 class="font-semibold text-gray-800 text-sm border-b pb-2">Rekapitulasi</h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="p-3 bg-gray-50 rounded-lg">
                                    <span class="text-xs text-gray-500">Total Biaya</span>
                                    <p class="text-lg font-bold text-gray-800" x-text="'Rp ' + formatNumber(totalBiaya)"></p>
                                </div>
                                <div class="p-3 bg-indigo-50 rounded-lg">
                                    <span class="text-xs text-indigo-500">Harga Penawaran</span>
                                    <p class="text-lg font-bold text-indigo-700" x-text="'Rp ' + formatNumber(hargaPenawaran)"></p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="catatan" value="Catatan" />
                            <textarea id="catatan" name="catatan" rows="3" x-model="form.catatan" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"></textarea>
                            <x-input-error :messages="$errors->get('catatan')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-3">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                                {{ isset($sph) ? 'Perbarui' : 'Simpan' }}
                            </button>
                            <a href="{{ route('sph.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition">Batal</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    function sphCalculator() {
        return {
            form: {
                nomor_sph: '',
                tanggal_sph: '',
                honorarium: 0,
                material: 0,
                transport: 0,
                jumlah_minggu_transport: 4,
                uang_harian: 0,
                jumlah_minggu_harian: 4,
                akomodasi: 0,
                biaya_lain: 0,
                biaya_lain_keterangan: '',
                overhead: 0,
                margin_keuntungan: 0,
                catatan: '',
            },
            honorariumDisplay: '',
            materialDisplay: '',
            transportDisplay: '',
            uang_harianDisplay: '',
            akomodasiDisplay: '',
            biaya_lainDisplay: '',
            overheadDisplay: '',
            teknisiAssignments: [],
            jumlahTeknisi: 1,

            initForm(data) {
                if (data) {
                    this.form.nomor_sph = data.nomor_sph || '';
                    this.form.tanggal_sph = data.tanggal_sph || '';
                    this.form.honorarium = data.honorarium || 0;
                    this.form.material = data.material || 0;
                    this.form.transport = data.transport || 0;
                    this.form.jumlah_minggu_transport = data.jumlah_minggu_transport || 4;
                    this.form.uang_harian = data.uang_harian || 0;
                    this.form.jumlah_minggu_harian = data.jumlah_minggu_harian || 4;
                    this.form.akomodasi = data.akomodasi || 0;
                    this.form.biaya_lain = data.biaya_lain || 0;
                    this.form.biaya_lain_keterangan = data.biaya_lain_keterangan || '';
                    this.form.overhead = data.overhead || 0;
                    this.form.margin_keuntungan = data.margin_keuntungan || 0;
                    this.form.catatan = data.catatan || '';

                    this.honorariumDisplay = this.formatRupiah(data.honorarium || 0);
                    this.materialDisplay = this.formatRupiah(data.material || 0);
                    this.transportDisplay = this.formatRupiah(data.transport || 0);
                    this.uang_harianDisplay = this.formatRupiah(data.uang_harian || 0);
                    this.akomodasiDisplay = this.formatRupiah(data.akomodasi || 0);
                    this.biaya_lainDisplay = this.formatRupiah(data.biaya_lain || 0);
                    this.overheadDisplay = this.formatRupiah(data.overhead || 0);

                    if (data.teknisi_assignments && data.teknisi_assignments.length > 0) {
                        this.teknisiAssignments = data.teknisi_assignments.map(function(t) {
                            return {
                                nama: t.nama || '',
                                upah_per_bulan: parseFloat(t.upah_per_bulan) || 0,
                                jumlah_bulan: parseFloat(t.jumlah_bulan) || 0,
                                upahDisplay: 'Rp ' + (parseFloat(t.upah_per_bulan) || 0).toLocaleString('id-ID'),
                                total: (parseFloat(t.upah_per_bulan) || 0) * (parseFloat(t.jumlah_bulan) || 0),
                            };
                        });
                        this.updateJumlahTeknisi();
                    } else {
                        this.addTeknisi();
                        this.addTeknisi();
                        this.addTeknisi();
                    }
                } else {
                    this.addTeknisi();
                    this.addTeknisi();
                    this.addTeknisi();
                }
            },
            addTeknisi() {
                this.teknisiAssignments.push({
                    nama: '',
                    upah_per_bulan: 0,
                    jumlah_bulan: 1,
                    upahDisplay: '',
                    total: 0,
                });
                this.updateJumlahTeknisi();
            },
            removeTeknisi(idx) {
                this.teknisiAssignments.splice(idx, 1);
                this.updateJumlahTeknisi();
            },
            updateTeknisiUpah(idx, value) {
                var cleaned = value.replace(/[^0-9]/g, '');
                var num = parseInt(cleaned, 10);
                if (isNaN(num)) num = 0;
                this.teknisiAssignments[idx].upah_per_bulan = num;
                this.teknisiAssignments[idx].upahDisplay = num > 0 ? 'Rp ' + num.toLocaleString('id-ID') : '';
                this.teknisiAssignments[idx].total = num * (parseFloat(this.teknisiAssignments[idx].jumlah_bulan) || 0);
            },
            formatRupiah(value) {
                if (!value && value !== 0) return '';
                var num = parseFloat(value);
                if (isNaN(num)) return '';
                return 'Rp ' + num.toLocaleString('id-ID');
            },
            formatNumber(value) {
                if (!value && value !== 0) return '0';
                var num = parseFloat(value);
                if (isNaN(num)) return '0';
                return num.toLocaleString('id-ID');
            },
            updateField(field, value) {
                var cleaned = value.replace(/[^0-9]/g, '');
                var num = parseInt(cleaned, 10);
                if (isNaN(num)) {
                    this.form[field] = 0;
                } else {
                    this.form[field] = num;
                }
                var displayField = field + 'Display';
                if (this[displayField] !== undefined) {
                    this[displayField] = this.formatRupiah(this.form[field]);
                }
            },
            get totalTeknisiUpah() {
                var total = 0;
                for (var i = 0; i < this.teknisiAssignments.length; i++) {
                    var t = this.teknisiAssignments[i];
                    total += (parseFloat(t.upah_per_bulan) || 0) * (parseFloat(t.jumlah_bulan) || 0);
                }
                return total;
            },
            updateJumlahTeknisi() {
                this.jumlahTeknisi = this.teknisiAssignments.length || 1;
            },
            get totalTransport() {
                return (parseFloat(this.form.transport) || 0) * 5 * (parseFloat(this.form.jumlah_minggu_transport) || 0) * this.jumlahTeknisi;
            },
            get totalUangHarian() {
                return (parseFloat(this.form.uang_harian) || 0) * 5 * (parseFloat(this.form.jumlah_minggu_harian) || 0) * this.jumlahTeknisi;
            },
            get totalBiaya() {
                var t = (parseFloat(this.form.honorarium) || 0)
                    + (parseFloat(this.form.material) || 0)
                    + this.totalTransport
                    + this.totalUangHarian
                    + (parseFloat(this.form.akomodasi) || 0)
                    + (parseFloat(this.form.biaya_lain) || 0)
                    + (parseFloat(this.form.overhead) || 0)
                    + this.totalTeknisiUpah;
                return t;
            },
            get hargaPenawaran() {
                var biaya = this.totalBiaya;
                var margin = parseFloat(this.form.margin_keuntungan) || 0;
                return biaya * (1 + margin / 100);
            }
        };
    }
    </script>
</x-app-layout>
