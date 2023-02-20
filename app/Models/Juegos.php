<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Juegos extends Model
{
    use HasFactory;
    
    protected $table="juegos";

    public function Servicios()
    {
        return $this->belongsTo(Servicios::class);
    }
}

