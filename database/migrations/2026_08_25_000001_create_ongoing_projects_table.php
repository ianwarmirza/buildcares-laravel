<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ongoing_projects', function (Blueprint $table) {
            $table->id();
            $table->string('site_address');
            $table->string('proposal');
            $table->string('status')->default('In Progress');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ongoing_projects');
    }
};
