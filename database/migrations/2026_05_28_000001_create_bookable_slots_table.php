<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookable_slots', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->unsignedSmallInteger('slot');
            $table->timestamps();

            $table->unique(['date', 'slot']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookable_slots');
    }
};
