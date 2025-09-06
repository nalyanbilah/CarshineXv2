<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->string('employee_id')->unique();
                $table->string('position')->nullable();
                $table->string('department')->nullable();
                $table->string('avatar')->nullable();
                $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
                $table->integer('current_workload')->default(0);
                $table->integer('max_workload')->default(100);
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};