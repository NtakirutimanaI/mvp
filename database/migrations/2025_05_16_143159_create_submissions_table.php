<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();

            // Foreign keys
            $table->unsignedBigInteger('citizen_id');    // from users table
            $table->unsignedBigInteger('institution_id'); // from users table

            // Submission content
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('attachment')->nullable(); // file upload (optional)

            // Status tracking
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();

            // Audit
            $table->timestamps();

            // Foreign key constraints (assuming both users exist in users table)
            $table->foreign('citizen_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('institution_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
