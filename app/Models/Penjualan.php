<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['tanggal', 'total_pemasukan', 'total_hpp', 'total_keuntungan', 'catatan'])]
class Penjualan extends Model
{
    use HasFactory;

    protected $casts = [
        'tanggal' => 'date',
        'total_pemasukan' => 'decimal:2',
        'total_hpp' => 'decimal:2',
        'total_keuntungan' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(PenjualanItem::class, 'penjualan_id');
    }
}
