<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Webaccess\IFMQuiz\Models\Attempt;

class CreateAttemptTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->uuid('id')->primary('id');
            $table->uuid('quiz_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->integer('status')->default(Attempt::STATUS_SENT);
            $table->datetime('started_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->datetime('marked_at')->nullable();
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
        Schema::drop('attempts');
    }
}
