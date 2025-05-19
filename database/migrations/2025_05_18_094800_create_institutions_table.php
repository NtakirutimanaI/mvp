<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionsTable extends Migration
{
    public function up()
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('firstname'); // Full name of the institution
            $table->string('lastname'); // Full name of the institution
            $table->string('code')->nullable(); // Optional short code or acronym
            $table->string('type'); // e.g., Ministry, Agency, Department
            $table->string('sector')->nullable(); // e.g., Health, Education, Transport
            $table->string('email')->nullable(); // Contact email
            $table->string('phone')->nullable(); // Contact phone
            $table->string('website')->nullable(); // Optional website
            $table->string('address')->nullable(); // Physical address
            $table->string('location')->nullable(); // Could be city or region
            $table->text('description')->nullable(); // Brief description of responsibilities
            $table->boolean('is_active')->default(true); // Status of the institution
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('institutions');
    }
}
