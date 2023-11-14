<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sub_comments', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->unsignedTinyInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedTinyInteger('comment_id');
            $table->foreign('comment_id')->references('id')->on('comments');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_comments');
    }
};
