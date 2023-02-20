<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    use HasFactory;
    
    protected $table="servicios";

    public function Consolas()
    {
        return $this->hasMany(Consola::class);
    }

    public function Juegos()
    {
        return $this->hasMany(Juegos::class);
    }
}
