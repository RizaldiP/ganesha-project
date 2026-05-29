<x-app-layout>
    @push('breadcrumbs')
        <x-breadcrumbs :items="[
            ['label' => 'Dashboard', 'url' => route('dashboard')],
            ['label' => 'Kalender'],
        ]" />
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Kalender Kegiatan') }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Stats --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6" id="stats-container">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="stat-total">0</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Bulan Ini</p>
                    <p class="text-2xl font-bold text-indigo-600 mt-1" id="stat-month">0</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Hari Ini</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1" id="stat-today">0</p>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Seharian</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1" id="stat-allday">0</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div id="calendar-container" class="p-4 md:p-6"></div>
            </div>
        </div>
    </div>

    {{-- Modal Tambah/Edit Event --}}
    <div x-data="eventModal()"
         x-show="open"
         x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
         x-on:keydown.escape.window="close()">
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-gray-900/60"
             x-on:click="close()"></div>
        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg z-10 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900" x-text="editing ? 'Edit Kegiatan' : 'Tambah Kegiatan'"></h3>
                <button type="button" x-on:click="close()" class="p-1.5 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form x-on:submit.prevent="handleSubmit" class="p-6 space-y-5">
                <input type="hidden" name="event_id" x-model="form.id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Kegiatan</label>
                    <input type="text" x-model="form.title" required
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                           placeholder="Masukkan judul kegiatan...">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi</label>
                    <textarea x-model="form.description" rows="3"
                              class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm resize-none"
                              placeholder="Deskripsi kegiatan (opsional)"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                        <input type="date" x-model="form.start_date" required
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Tanggal Selesai</label>
                        <input type="date" x-model="form.end_date"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Mulai</label>
                        <input type="time" x-model="form.start_time"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Jam Selesai</label>
                        <input type="time" x-model="form.end_time"
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <input type="checkbox" x-model="form.is_all_day" id="is_all_day"
                           class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="is_all_day" class="text-sm text-gray-700">All Day (Seharian)</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Warna</label>
                    <div class="flex gap-2 flex-wrap">
                        <template x-for="c in colors" :key="c">
                            <button type="button" x-on:click="form.color = c"
                                    :class="{'ring-2 ring-offset-2 ring-gray-400 scale-110': form.color === c}"
                                    class="w-8 h-8 rounded-full transition-all duration-150 hover:scale-110"
                                    :style="'background-color: ' + c"></button>
                        </template>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-2 border-t border-gray-100">
                    <button type="button" x-show="editing" x-on:click="destroy"
                            class="text-sm text-red-600 hover:text-red-800 font-medium px-3 py-2 rounded-lg hover:bg-red-50 transition">
                        Hapus
                    </button>
                    <div class="flex items-center gap-3 ml-auto">
                        <button type="button" x-on:click="close()"
                                class="text-sm text-gray-600 hover:text-gray-800 font-medium px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                            Batal
                        </button>
                        <button type="submit" :disabled="loading"
                                class="text-sm font-semibold text-white px-5 py-2 rounded-lg transition"
                                :class="loading ? 'bg-indigo-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700'">
                            <span x-show="!loading" x-text="editing ? 'Simpan' : 'Tambah'"></span>
                            <span x-show="loading">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    const CALENDAR_ROUTES = {
        events: '{{ route("calendar.events") }}',
        store: '{{ route("calendar.store") }}',
        update: '{{ route("calendar.update", "_id_") }}'.replace('_id_', ''),
        destroy: '{{ route("calendar.destroy", "_id_") }}'.replace('_id_', ''),
    };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/id.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.15/index.global.min.js"></script>
    <script>
    function updateStats(events) {
        const now = new Date();
        const todayStr = now.toISOString().slice(0, 10);
        const monthStr = now.toISOString().slice(0, 7);

        const total = events.length;
        const month = events.filter(e => e.start && e.start.startsWith(monthStr)).length;
        const today = events.filter(e => e.start && e.start.startsWith(todayStr)).length;
        const allday = events.filter(e => e.allDay).length;

        const statTotal = document.getElementById('stat-total');
        if (statTotal) statTotal.textContent = total;
        const statMonth = document.getElementById('stat-month');
        if (statMonth) statMonth.textContent = month;
        const statToday = document.getElementById('stat-today');
        if (statToday) statToday.textContent = today;
        const statAllday = document.getElementById('stat-allday');
        if (statAllday) statAllday.textContent = allday;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        var calendarEl = document.getElementById('calendar-container');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            locale: 'id',
            locales: [{
                id: 'id',
                monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                dayNames: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'],
                dayNamesShort: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'],
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari',
                },
            }],
            height: 'auto',
            firstDay: 1,
            navLinks: true,
            editable: true,
            selectable: true,
            selectMirror: true,
            dayMaxEvents: 3,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            moreLinkText: function(n) {
                return '+ ' + n + ' lagi';
            },
            noEventsText: 'Tidak ada kegiatan',

            events: function(fetchInfo, successCallback, failureCallback) {
                fetch(CALENDAR_ROUTES.events)
                    .then(res => res.json())
                    .then(data => {
                        successCallback(data);
                        updateStats((data || []).map(function(e) {
                            return {
                                start: e.start ? e.start.slice(0, 10) : null,
                                allDay: e.allDay
                            };
                        }));
                    })
                    .catch(failureCallback);
            },

            dateClick: function(info) {
                if (info.dayEl && info.dayEl.classList.contains('fc-other-month')) return;
                window.dispatchEvent(new CustomEvent('open-event-modal', {
                    detail: {
                        start: info.allDay ? info.dateStr : info.dateStr.slice(0, 10),
                        end: info.allDay ? info.dateStr : info.dateStr.slice(0, 10),
                        allDay: info.allDay
                    }
                }));
            },

            select: function(info) {
                window.dispatchEvent(new CustomEvent('open-event-modal', {
                    detail: {
                        start: info.startStr,
                        end: info.endStr,
                        allDay: info.allDay
                    }
                }));
            },

            eventClick: function(info) {
                info.jsEvent.preventDefault();
                window.dispatchEvent(new CustomEvent('open-event-modal', {
                    detail: {
                        eventId: info.event.id,
                        title: info.event.title,
                        description: info.event.extendedProps.description || '',
                        start: info.event.startStr,
                        end: info.event.endStr,
                        allDay: info.event.allDay,
                        color: info.event.backgroundColor,
                        startTime: info.event.extendedProps.start_time,
                        endTime: info.event.extendedProps.end_time,
                    }
                }));
            },

            eventDrop: function(info) {
                let startStr = info.event.startStr;
                let endStr = info.event.endStr;
                let allDay = info.event.allDay;

                fetch(CALENDAR_ROUTES.update + info.event.id, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        start_date: allDay ? startStr : startStr.slice(0, 10),
                        end_date: endStr ? (allDay ? endStr : endStr.slice(0, 10)) : null,
                        start_time: allDay ? null : (startStr.includes('T') ? startStr.slice(11, 16) : null),
                        end_time: (!allDay && endStr && endStr.includes('T')) ? endStr.slice(11, 16) : null,
                        is_all_day: allDay
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        info.revert();
                    } else {
                        updateStats(calendar.getEvents().map(e => ({
                            start: e.start ? e.start.toISOString().slice(0, 10) : null,
                            allDay: e.allDay
                        })));
                    }
                })
                .catch(() => info.revert());
            },

            eventResize: function(info) {
                let endStr = info.event.endStr;

                fetch(CALENDAR_ROUTES.update + info.event.id, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        end_date: info.event.allDay ? endStr : endStr.slice(0, 10),
                        end_time: (!info.event.allDay && endStr && endStr.includes('T')) ? endStr.slice(11, 16) : null,
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        info.revert();
                    } else {
                        updateStats(calendar.getEvents().map(e => ({
                            start: e.start ? e.start.toISOString().slice(0, 10) : null,
                            allDay: e.allDay
                        })));
                    }
                })
                .catch(() => info.revert());
            }
        });

        calendar.render();
        window._calendarInstance = calendar;

        window.addEventListener('open-event-modal', function(e) {
            window.dispatchEvent(new CustomEvent('set-event-data', { detail: e.detail }));
        });
    });
    </script>

    <script>
    function eventModal() {
        return {
            open: false,
            loading: false,
            editing: false,
            colors: ['#6366f1', '#8b5cf6', '#ec4899', '#ef4444', '#f97316', '#eab308', '#22c55e', '#14b8a6', '#06b6d4', '#3b82f6'],
            form: {
                id: null,
                title: '',
                description: '',
                start_date: '',
                end_date: '',
                start_time: '',
                end_time: '',
                color: '#6366f1',
                is_all_day: false,
            },

            init() {
                window.addEventListener('set-event-data', (e) => this.openModal(e.detail));
            },

            openModal(data) {
                if (data.eventId) {
                    this.editing = true;
                    this.form.id = data.eventId;
                    this.form.title = data.title || '';
                    this.form.description = data.description || '';
                    this.form.start_date = data.start ? data.start.slice(0, 10) : '';
                    this.form.end_date = data.end ? data.end.slice(0, 10) : '';
                    this.form.start_time = data.startTime || (data.start && data.start.includes('T') ? data.start.slice(11, 16) : '');
                    this.form.end_time = data.endTime || (data.end && data.end.includes('T') ? data.end.slice(11, 16) : '');
                    this.form.color = data.color || '#6366f1';
                    this.form.is_all_day = data.allDay || false;
                } else {
                    this.editing = false;
                    this.form.id = null;
                    this.form.title = '';
                    this.form.description = '';
                    this.form.start_date = data.start || '';
                    this.form.end_date = data.end || '';
                    this.form.start_time = data.allDay ? '' : '08:00';
                    this.form.end_time = data.allDay ? '' : '17:00';
                    this.form.color = '#6366f1';
                    this.form.is_all_day = data.allDay || false;
                }
                this.open = true;
            },

            close() {
                this.open = false;
                this.loading = false;
            },

            handleSubmit() {
                this.loading = true;
                const isEdit = this.editing;
                const url = isEdit
                    ? CALENDAR_ROUTES.update + this.form.id
                    : CALENDAR_ROUTES.store;
                const method = isEdit ? 'PATCH' : 'POST';

                const payload = {
                    title: this.form.title,
                    description: this.form.description,
                    start_date: this.form.start_date,
                    end_date: this.form.end_date || null,
                    start_time: this.form.is_all_day ? null : (this.form.start_time || null),
                    end_time: this.form.is_all_day ? null : (this.form.end_time || null),
                    color: this.form.color,
                    is_all_day: this.form.is_all_day,
                };

                fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && window._calendarInstance) {
                        window._calendarInstance.refetchEvents();
                    }
                    this.close();
                })
                .catch(() => this.close());
            },

            destroy() {
                if (!confirm('Hapus kegiatan ini?')) return;
                this.loading = true;

                fetch(CALENDAR_ROUTES.destroy + this.form.id, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && window._calendarInstance) {
                        window._calendarInstance.refetchEvents();
                    }
                    this.close();
                })
                .catch(() => this.close());
            }
        }
    }
    </script>
    @endpush

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.15/main.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid@6.1.15/main.min.css" rel="stylesheet">
    <style>
        .fc {
            --fc-border-color: #e5e7eb;
            --fc-button-text-color: #374151;
            --fc-button-bg-color: #f9fafb;
            --fc-button-border-color: #d1d5db;
            --fc-button-hover-bg-color: #f3f4f6;
            --fc-button-hover-border-color: #9ca3af;
            --fc-button-active-bg-color: #e5e7eb;
            --fc-button-active-border-color: #6b7280;
            --fc-today-bg-color: #eef2ff;
            --fc-event-bg-color: #6366f1;
            --fc-event-border-color: #6366f1;
            --fc-event-text-color: #fff;
            --fc-event-selected-overlay-color: rgba(0,0,0,0.15);
            --fc-more-link-bg-color: #f3f4f6;
            --fc-more-link-text-color: #374151;
            --fc-neutral-bg-color: #fff;
            --fc-page-bg-color: #fff;
            --fc-list-event-hover-bg-color: #f9fafb;
            font-family: inherit;
        }
        .fc .fc-toolbar-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #111827;
        }
        .fc .fc-button {
            font-size: 0.8125rem;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-weight: 500;
            text-transform: capitalize;
            transition: all 0.15s ease;
        }
        .fc .fc-button-primary:not(:disabled).fc-button-active,
        .fc .fc-button-primary:not(:disabled):active {
            background-color: #6366f1;
            border-color: #6366f1;
            color: #fff;
        }
        .fc .fc-button-primary:not(:disabled):focus {
            box-shadow: 0 0 0 2px rgba(99,102,241,0.3);
        }
        .fc .fc-daygrid-day-number {
            font-size: 0.8125rem;
            font-weight: 500;
            color: #374151;
            padding: 0.5rem;
        }
        .fc .fc-col-header-cell-cushion {
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.625rem 0;
        }
        .fc .fc-daygrid-day.fc-day-today {
            background-color: #eef2ff;
        }
        .fc .fc-daygrid-day.fc-day-today .fc-daygrid-day-number {
            background-color: #6366f1;
            color: #fff;
            border-radius: 9999px;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0.25rem 0.5rem;
        }
        .fc .fc-daygrid-event {
            border-radius: 6px;
            padding: 2px 6px;
            font-size: 0.75rem;
            font-weight: 500;
            border: none;
            margin: 1px 4px;
            cursor: pointer;
            transition: opacity 0.15s;
        }
        .fc .fc-daygrid-event:hover {
            opacity: 0.85;
        }
        .fc .fc-timegrid-event {
            border-radius: 6px;
            padding: 2px 4px;
            font-size: 0.75rem;
            font-weight: 500;
            border: none;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .fc .fc-more-popover {
            border-radius: 0.75rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }
        .fc .fc-more-popover .fc-popover-header {
            background: #f9fafb;
            padding: 0.625rem 0.875rem;
            font-size: 0.8125rem;
            font-weight: 600;
            border-bottom: 1px solid #e5e7eb;
        }
        .fc .fc-more-popover .fc-popover-body {
            padding: 0.375rem;
        }
        .fc .fc-popover-close {
            font-size: 1rem;
            opacity: 0.5;
        }
        .fc .fc-popover-close:hover {
            opacity: 1;
        }
        .fc .fc-scrollgrid {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .fc .fc-scrollgrid-section-header .fc-scroller {
            border-radius: 0.5rem 0.5rem 0 0;
        }
        .fc .fc-daygrid-more-link {
            font-size: 0.75rem;
            font-weight: 500;
            color: #6366f1;
            padding: 2px 6px;
        }
        .fc .fc-daygrid-more-link:hover {
            background: #eef2ff;
            border-radius: 4px;
            text-decoration: none;
        }
        .fc td, .fc th {
            border-style: solid;
            border-width: 1px;
        }
        .fc .fc-timegrid-slot-label {
            font-size: 0.6875rem;
            color: #6b7280;
        }
        .fc .fc-timegrid-now-indicator-line {
            border-color: #ef4444;
        }
        .fc .fc-timegrid-now-indicator-arrow {
            border-color: #ef4444;
            color: #ef4444;
        }
        @media (max-width: 768px) {
            .fc .fc-toolbar {
                flex-direction: column;
                gap: 0.75rem;
            }
            .fc .fc-toolbar-title {
                font-size: 1rem;
            }
            .fc .fc-toolbar-chunk {
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 0.375rem;
            }
        }
    </style>
    @endpush
</x-app-layout>
