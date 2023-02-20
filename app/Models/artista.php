<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class artista extends Model
{
    use HasFactory;
    protected $table="artistas";

    public function productora(){
        return $this->belongsTo(productora::class,'id');
    }

    public function plataforma(){
        return $this->belongsTo(productora::class,'id');
    }

    public function musica(){
        return $this->belongsTo(musica::class,'id');
    }
}
