<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('delivery_man_id')->default(0);
            $table->integer('order_request')->default(0);
            $table->date('order_req_date')->nullable();
            $table->date('order_req_accept_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('delivery_man_id');
            $table->dropColumn('order_request');
            $table->dropColumn('order_req_date');
            $table->dropColumn('order_req_accept_date');
        });
    }
};
