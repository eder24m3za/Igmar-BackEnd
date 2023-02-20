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
        Schema::create('compania', function (Blueprint $table) {
            $table->id();
            $table->string('Nombre',15);
            $table->string('Sede',30);
            $table->string('Fundador',20);
            $table->date('Fundacion');
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
        Schema::dropIfExists('compania');
    }
};
