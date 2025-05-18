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
        Schema::create('classification_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataId')->constrained('classifications')->cascadeOnDelete();
            $table->string('name');
            $table->string('fullName')->nullable();
            $table->string('code')->nullable();
            $table->string('color')->nullable();
            $table->boolean('isHide')->default(false);
            $table->foreignId('parent_id')->nullable()->constrained('classification_data')->cascadeOnDelete();
            $table->integer('level');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification_data');
    }
};
