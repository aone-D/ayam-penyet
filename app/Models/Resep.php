<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama', 'deskripsi', 'harga_jual', 'foto'])]
class Resep extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'deskripsi', 'harga_jual', 'foto'];

    protected $casts = [
        'harga_jual' => 'decimal:2',
    ];

    public function bahanBakus(): BelongsToMany
    {
        return $this->belongsToMany(BahanBaku::class, 'resep_bahan_baku', 'resep_id', 'bahan_baku_id')
                    ->withPivot('jumlah_dipakai')
                    ->withTimestamps();
    }

    public function penjualanItems(): HasMany
    {
        return $this->hasMany(PenjualanItem::class, 'resep_id');
    }

    public function getHppAttribute(): float
    {
        $hpp = 0;
        foreach ($this->bahanBakus as $bahan) {
            $hpp += $bahan->pivot->jumlah_dipakai * $bahan->harga_per_satuan_pakai;
        }
        return $hpp;
    }

    public function getMarginAttribute(): float
    {
        return $this->harga_jual - $this->hpp;
    }

    public function getMarginPersenAttribute(): float
    {
        if ($this->harga_jual == 0) {
            return 0;
        }
        return ($this->margin / $this->harga_jual) * 100;
    }
}
