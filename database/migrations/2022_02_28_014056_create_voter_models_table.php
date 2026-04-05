<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoterModelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_models', function (Blueprint $table) {
            $table->id();
            $table->integer('idNum');
            $table->string('fname');
            $table->string('lname');
            $table->string('mname')->nullable();
            $table->string('imageUrl')->nullable();
            $table->string('password')->nullable();
            $table->string('college_init')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voter_models');
    }
}
