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
       Schema::create('service_providers', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('phone')->unique();
    $table->string('email')->unique()->nullable();
    $table->string('password');
    $table->foreignId('category_id')->constrained();
    $table->text('bio')->nullable();
    $table->decimal('hourly_rate', 8, 2);
    $table->string('location');
    $table->boolean('is_available')->default(true);
    $table->decimal('rating', 3, 2)->default(0);
    $table->rememberToken();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_providers');
    }
};
