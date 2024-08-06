<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;


    protected $fillable = [
        'nombre',
        'img_light',
        'img_light_selected',
        'img_dark',
        'img_dark_selected',
    ];

    public function platos()
    {
        return $this->hasMany(Plato::class);
    }


}
