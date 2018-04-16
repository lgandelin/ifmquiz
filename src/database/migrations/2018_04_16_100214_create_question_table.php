<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('type')->nullable();
            $table->integer('number')->nullable();
            $table->text('items')->nullable();
            $table->text('correct_items')->nullable();
            $table->integer('quiz_id')->nullable();
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
        Schema::drop('questions');
    }
}
