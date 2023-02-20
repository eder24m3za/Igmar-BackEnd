<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productora extends Model
{
    use HasFactory;
    protected $table="productora";
    
    public function artistas()
    {
        return $this->hasMany(artista::class);
    }
}
