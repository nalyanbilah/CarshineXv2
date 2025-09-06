<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->foreignId('assigned_by')->constrained('users');
            $table->datetime('due_date');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled']);
            $table->integer('weight')->default(1); // For performance calculation
            $table->integer('required_skill_id')->nullable();
            $table->integer('workload_units')->default(10);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};