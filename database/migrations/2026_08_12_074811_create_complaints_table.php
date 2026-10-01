<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null');
            $table->string('title');
            $table->string('category');
            $table->string('department');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->text('description');
            $table->string('attachment')->nullable();
            $table->enum('status', ['Pending', 'Assigned', 'In Progress', 'Resolved', 'Reopened', 'Closed'])
                  ->default('Pending');
            $table->text('resolution_summary')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};