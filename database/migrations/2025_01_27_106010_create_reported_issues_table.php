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
        Schema::create('reported_issues', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->unsignedInteger('local_community_id');
            $table->enum('status', ['reported','in_progress','resolved'])->default('reported');
            $table->string('attachments')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

        // Define foreign key constraints
       // $table->foreign('user_id')
        //->references('id')
        //->on('users');

         //$table->foreign('county_id')
        //->references('id')
        //->on('counties');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reported_issues');
    }
};
