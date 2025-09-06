<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('users', 'employee_id')) {
                $table->string('employee_id')->unique()->after('email');
            }
            if (!Schema::hasColumn('users', 'position')) {
                $table->string('position')->nullable()->after('employee_id');
            }
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('position');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('department');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active')->after('avatar');
            }
            if (!Schema::hasColumn('users', 'current_workload')) {
                $table->integer('current_workload')->default(0)->after('status');
            }
            if (!Schema::hasColumn('users', 'max_workload')) {
                $table->integer('max_workload')->default(100)->after('current_workload');
            }
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'employee_id',
                'position',
                'department',
                'avatar',
                'status',
                'current_workload',
                'max_workload'
            ]);
        });
    }
};