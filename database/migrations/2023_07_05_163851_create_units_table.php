<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Admin\Unit;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->tinyText('title');
            $table->tinyText('as');
        });

        Unit::insert([
            ['title' => 'Kilogram', 'as' => 'Kg'],
            ['title' => 'Liter', 'as' => 'Ltr'],
            ['title' => 'Piece', 'as' => 'Pc'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
