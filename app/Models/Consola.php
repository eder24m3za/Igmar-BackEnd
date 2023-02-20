<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consola extends Model
{
    use HasFactory;

    protected $table="consolas";

    public function Companias()
    {
        return $this->belongsTo(Compania::class,'compania_id');
    }

    public function Servicios()
    {
        return $this->belongsTo(Servicios::class,'servicios_id');
    }
}



