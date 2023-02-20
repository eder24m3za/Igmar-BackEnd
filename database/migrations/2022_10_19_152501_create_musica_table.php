<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('musica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('singer');
            $table->foreign('singer')->references('id')->on('artistas');
            $table->unsignedbigInteger('productora'); 
            $table->foreign('productora')->references('id')->on('productora');          
            $table->string('titulo',30);
            $table->string('genero',50);
            $table->integer('duracion');
            $table->enum("status",["activo","inactivo"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('musica');
    }
};
