<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['bahan_baku_id', 'tipe', 'jumlah', 'keterangan'])]
class StokHistory extends Model
{
    use HasFactory;

    protected $fillable = ['bahan_baku_id', 'tipe', 'jumlah', 'keterangan'];

    public $timestamps = false;

    protected $casts = [
        'jumlah' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function bahanBaku(): BelongsTo
    {
        return $this->belongsTo(BahanBaku::class, 'bahan_baku_id');
    }
}
