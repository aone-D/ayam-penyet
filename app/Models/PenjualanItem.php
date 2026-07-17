<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['penjualan_id', 'resep_id', 'qty', 'harga_jual_saat_itu', 'hpp_saat_itu', 'subtotal'])]
class PenjualanItem extends Model
{
    use HasFactory;

    protected $casts = [
        'qty' => 'integer',
        'harga_jual_saat_itu' => 'decimal:2',
        'hpp_saat_itu' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function penjualan(): BelongsTo
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function resep(): BelongsTo
    {
        return $this->belongsTo(Resep::class, 'resep_id');
    }
}
