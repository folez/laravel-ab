<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ab_goals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('hit');
            $table->foreignId('variant_id')->constrained('ab_variants')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ab_goals');
    }
};
