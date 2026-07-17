<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'satuan_beli', 'penggunaan', 'satuan_pakai', 'konversi', 'harga_beli', 'harga_per_satuan_pakai', 'stok_saat_ini', 'stok_minimum'])]
class BahanBaku extends Model
{
    use HasFactory;

    protected $casts = [
        'penggunaan' => 'decimal:2',
        'konversi' => 'decimal:2',
        'harga_beli' => 'decimal:2',
        'harga_per_satuan_pakai' => 'decimal:4',
        'stok_saat_ini' => 'decimal:2',
        'stok_minimum' => 'decimal:2',
    ];

    public function stokHistories(): HasMany
    {
        return $this->hasMany(StokHistory::class, 'bahan_baku_id');
    }

    public function reseps(): BelongsToMany
    {
        return $this->belongsToMany(Resep::class, 'resep_bahan_baku', 'bahan_baku_id', 'resep_id')
                    ->withPivot('jumlah_dipakai')
                    ->withTimestamps();
    }

    public function getStatusStokAttribute(): string
    {
        if ($this->stok_minimum !== null && $this->stok_saat_ini <= $this->stok_minimum) {
            return 'menipis';
        }
        return 'aman';
    }

    public function hitungHargaPerSatuanPakai(): float
    {
        return $this->harga_beli / $this->konversi;
    }
}
