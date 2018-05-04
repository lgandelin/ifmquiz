<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuizTableParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizs', function (Blueprint $table) {
            $table->text('intro_text')->after('time')->nullable();
            $table->text('outro_text')->after('intro_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quiz', function (Blueprint $table) {
            $table->dropColumn('intro_text');
            $table->dropColumn('outro_text');
        });
    }
}
