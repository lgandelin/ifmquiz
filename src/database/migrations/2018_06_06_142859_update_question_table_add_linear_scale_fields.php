<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateQuestionTableAddLinearScaleFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->integer('linear_scale_start_number')->after('items_right')->nullable();
            $table->integer('linear_scale_end_number')->after('linear_scale_start_number')->nullable();
            $table->string('linear_scale_start_label')->after('linear_scale_end_number')->nullable();
            $table->string('linear_scale_end_label')->after('linear_scale_start_label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('linear_scale_start_number');
            $table->dropColumn('linear_scale_end_number');
            $table->dropColumn('linear_scale_start_label');
            $table->dropColumn('linear_scale_end_label');
        });
    }
}
