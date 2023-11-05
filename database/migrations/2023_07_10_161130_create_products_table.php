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
        Schema::create('products', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->uuid('uuid');
            $table->tinyText('name');
            $table->tinyText('slug');
            $table->tinyInteger('quantity');
            $table->unsignedTinyInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->decimal('price', 8, 2);
            $table->decimal('discounted_price', 8, 2);
            $table->boolean('featured')->default(0);
            $table->text('details');
            $table->unsignedTinyInteger('added_by');
            $table->foreign('added_by')->references('id')->on('users');
            $table->unsignedTinyInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
