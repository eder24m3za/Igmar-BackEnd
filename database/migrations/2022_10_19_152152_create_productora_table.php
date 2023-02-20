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
        Schema::create('productora', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cantante');
            $table->foreign('cantante')->references('id')->on('artistas');
            $table->string('nombre', 50);
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
        Schema::dropIfExists('productora');
    }
};
