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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('founder_name');
            $table->string('company_name');
            $table->text('short_note')->nullable();
            $table->string('founder_email');
            $table->string('location');
            $table->string('founder_phone');
            $table->string('business_category');
            $table->string('founder_gender');
            $table->string('website_url')->nullable();
            $table->integer('employee_number')->nullable();
            $table->string('formation_of_company')->nullable();
            $table->string('company_video_link')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('youtube_link')->nullable();
            $table->string('linkedin_link')->nullable();
            $table->string('attachment_file')->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->integer('rating')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();
            $table->string('api_token', 80)->unique()->nullable();
            $table->timestamps();


        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
