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
        Schema::create('travel', function (Blueprint $table) {
            $table->uuid('id')->unique();
            $table->boolean('is_public')->default(false);
            $table->string('slug');
            $table->string('name');
            $table->string('description');
            $table->smallInteger('number_of_days');
            $table->smallInteger('number_of_nights')->virtualAs('number_of_days - 1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel');
    }
};
