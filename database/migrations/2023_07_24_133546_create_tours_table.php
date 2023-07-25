<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('travel_id')->constrained();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropForeign(['travel_id']);
        });

        Schema::dropIfExists('tours');
    }
};
