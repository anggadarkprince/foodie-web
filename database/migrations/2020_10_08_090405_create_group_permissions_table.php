<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('group_id');
            $table->foreignId('permission_id');

            $table->foreign('group_id')->references('id')->on('groups')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_permissions');
    }
}
