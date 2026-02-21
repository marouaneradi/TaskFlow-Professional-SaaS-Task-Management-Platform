<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // task_created, task_updated, status_changed, member_added, etc.
            $table->string('subject_type')->nullable(); // task, team, member
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->json('properties')->nullable(); // old/new values
            $table->text('description');
            $table->timestamps();

            $table->index(['team_id', 'created_at']);
            $table->index(['user_id']);
            $table->index(['task_id']);
            $table->index(['action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
