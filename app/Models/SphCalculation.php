<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SphCalculation extends Model
{
    protected $fillable = [
        'task_id',
        'tipe',
        'nomor_sph',
        'tanggal_sph',
        'honorarium',
        'material',
        'alat',
        'upah',
        'transport',
        'jumlah_minggu_transport',
        'uang_harian',
        'jumlah_minggu_harian',
        'akomodasi',
        'biaya_lain',
        'biaya_lain_keterangan',
        'overhead',
        'margin_keuntungan',
        'total_biaya',
        'harga_penawaran',
        'catatan',
        'teknisi_assignments',
        'total_teknisi_upah',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_sph' => 'date:Y-m-d',
            'teknisi_assignments' => 'array',
            'margin_keuntungan' => 'decimal:2',
            'total_biaya' => 'decimal:2',
            'harga_penawaran' => 'decimal:2',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
