<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('courier_id')->nullable();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->string('order_number', 50);
            $table->string('payment_type', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('coupon_code', 25)->nullable();
            $table->decimal('order_discount', 10, 2)->default(0);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('delivery_discount', 10, 2)->default(0);
            $table->smallInteger('rating', false, true)->default(0);
            $table->enum('status', [
                'PENDING', 'REJECTED', 'CANCELED',
                'WAITING RESTAURANT CONFIRMATION', 'FINDING COURIER',
                'COURIER HEADING RESTAURANT', 'COURIER WAITING AT RESTAURANT',
                'COURIER HEADING CUSTOMER', 'COMPLETED'
            ]);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('courier_id')->references('id')->on('couriers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')
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
        Schema::dropIfExists('orders');
    }
}
