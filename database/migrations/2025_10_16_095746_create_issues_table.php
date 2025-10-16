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
        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title');
            $table->text('description')->nullable();
            //$table->enum('status', ['open', 'in_progress', 'closed'])->default('open');
            $table->string('status')->default('open');
            //$table->enum('priority', ['low', 'medium', 'high' , 'highest'])->default('medium');
            $table->string('priority')->default('medium');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('assigned_to')->constrained('users')->onDelete('null');
            $table->string('code')->unique();
            $table->json('due_window')->nullable();
            $table->timedate('status_change_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('issues');
    }
};
