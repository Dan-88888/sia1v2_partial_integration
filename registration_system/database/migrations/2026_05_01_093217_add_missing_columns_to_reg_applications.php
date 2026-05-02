<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reg_applications', function (Blueprint $table) {
            $table->string('campus')->nullable()->after('type');
            $table->string('college')->nullable()->after('campus');
            $table->string('course')->nullable()->after('college');
            $table->string('year_level')->nullable()->after('course');
            $table->string('temp_password')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('reg_applications', function (Blueprint $table) {
            $table->dropColumn(['campus', 'college', 'course', 'year_level', 'temp_password']);
        });
    }
};
