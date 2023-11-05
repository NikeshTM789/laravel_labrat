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
        Schema::create('categories', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->uuid('uuid');
            $table->tinyText('name');
            $table->softDeletes();
            // $table->timestamps();
        });

        DB::table('categories')->insert([
            ['uuid' => str()->uuid(), 'name' => 'Electronics'],
            ['uuid' => str()->uuid(), 'name' => 'Clothes'],
            ['uuid' => str()->uuid(), 'name' => 'Toys'],
            ['uuid' => str()->uuid(), 'name' => 'Kitchen Products'],
            ['uuid' => str()->uuid(), 'name' => 'Supermarket']
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
