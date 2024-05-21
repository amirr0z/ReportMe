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
        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('user_project_id')->onDelete('cascade');
            $table->unsignedBigInteger('user_project_id');
            $table->foreign('user_project_id')->references('id')->on('user_projects')->onDelete('cascade');
            // $table->foreignId('project_id')->onDelete('cascade');
            $table->text('description');
            $table->string('score')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warnings');
    }
};
