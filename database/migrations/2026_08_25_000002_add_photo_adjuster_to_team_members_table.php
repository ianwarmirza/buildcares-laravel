<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->integer('photo_position_x')->default(50)->after('photo');
            $table->integer('photo_position_y')->default(50)->after('photo_position_x');
            $table->integer('photo_zoom')->default(100)->after('photo_position_y');
        });
    }

    public function down(): void
    {
        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn(['photo_position_x', 'photo_position_y', 'photo_zoom']);
        });
    }
};
