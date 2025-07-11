<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
{
  public function up()
{
    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('role'); // admin, institution, citizen
        $table->string('menu'); // menu name from sidebar
        $table->boolean('can_read')->default(false);
        $table->boolean('can_create')->default(false);
        $table->boolean('can_edit')->default(false);
        $table->boolean('can_delete')->default(false);
        $table->boolean('can_approve')->default(false);
        $table->timestamps();
    });
}
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
