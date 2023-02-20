<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plataforma extends Model
{
    use HasFactory;
        protected $table="plataforma_streaming";

        public function artistas()
        {
            return $this->hasMany(artista::class);
        }
}
