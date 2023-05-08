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
        Schema::create('tasks', function (Blueprint $table) {
            $table->string('id_task')->primary();
            $table->timestamps();
            $table->string('name');
            $table->string('assigment');
            $table->timestamp('start_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('workspace');
            $table->foreign('workspace')->references('name')->on('workspaces')->onDelete('cascade')->onUpdate('cascade');
            $table->string('priority');
            $table->string('deskripsi');
            $table->string('status');
            $table->string('avatar');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
