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
        Schema::create('plataforma_streaming', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cantant');
            $table->foreign('cantant')->references('id')->on('artistas');
            $table->string('nombre_plataforma',30);
            $table->text('descripcion');
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
        Schema::dropIfExists('plataforma_streaming');
    }
};
