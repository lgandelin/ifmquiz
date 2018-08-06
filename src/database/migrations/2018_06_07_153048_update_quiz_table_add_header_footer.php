<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuizTableAddHeaderFooter extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizs', function (Blueprint $table) {
            $table->string('header_logo')->after('outro_text')->nullable();
            $table->mediumText('footer_text')->after('header_logo')->nullable();
            $table->string('footer_image')->after('footer_text')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizs', function (Blueprint $table) {
            $table->dropColumn('header_logo');
            $table->dropColumn('footer_text');
            $table->dropColumn('footer_image');
        });
    }
}
