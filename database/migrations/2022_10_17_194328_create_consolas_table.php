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
        Schema::create('consolas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compania_id');
            $table->foreign('compania_id')->references('id')->on('compania');
            $table->unsignedBigInteger('servicio_id');
            $table->foreign('servicio_id')->references('id')->on('servicios');
            $table->string('Nombre',20);
            $table->string('Almacenamiento',10);
            $table->string('RAM',20);
            $table->Integer('Precio');
            $table->string('Color',20);
            $table->date('Lanzamiento',20);
            $table->enum("Estatus",['Activo','Inactivo']);
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
        Schema::dropIfExists('consolas');
    }
};
