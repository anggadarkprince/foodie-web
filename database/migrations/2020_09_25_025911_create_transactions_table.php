<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('no_reference', 100);
            $table->enum('type', ['TOP UP', 'ORDER', 'REWARD', 'TRANSFER']);
            $table->decimal('total', 20, 2)->default(0);
            $table->enum('status', ['SUCCESS', 'FAILED', 'IN PROCESS', 'WITHDRAW']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
