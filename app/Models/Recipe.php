<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = ['nama_resep'];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
