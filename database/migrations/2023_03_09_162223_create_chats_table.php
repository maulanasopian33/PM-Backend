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
        Schema::create('chats', function (Blueprint $table) {
            $table->string('id_chat')->primary();
            $table->string('task_id');
            $table->foreign('task_id')->references('id_task')->on('tasks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('message');
            $table->string('from');
            $table->string('type');
            $table->boolean('reply');
            $table->date('time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chats');
    }
};
