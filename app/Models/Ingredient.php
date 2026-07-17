<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = ['recipe_id', 'bahan', 'qty', 'harga', 'jumlah_porsi'];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
