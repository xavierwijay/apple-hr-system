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
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->string('employee_id')->unique(); // Ini kolom yang dicari Seeder
        $table->string('nama');
        $table->string('role_title');
        $table->string('team_division');
        $table->string('location');
        $table->integer('salary');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
