<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealsTable extends Migration
{
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('url');
            $table->unsignedInteger('rating')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('meals');
    }
}
