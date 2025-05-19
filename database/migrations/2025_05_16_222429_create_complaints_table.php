<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
  // database/migrations/xxxx_create_complaints_table.php

public function up()
{
    Schema::create('complaints', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained();
        $table->foreignId('institution_id')->nullable()->constrained();
        $table->string('subject');
        $table->text('description');
        $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
        $table->string('category');
        $table->text('response')->nullable();
        $table->timestamps();
    });
}

    public function down()
    {
        Schema::dropIfExists('complaints');
    }
}
