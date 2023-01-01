<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cons_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cons_sub_category_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('fund_id')->constrained();
            $table->foreignId('room_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('item_code')->unique();
            $table->string('name');
            $table->bigInteger('initial_amount');
            $table->bigInteger('taken_amount');
            $table->bigInteger('stock_amount');
            $table->bigInteger('price');
            $table->date('purchase_date');
            $table->string('image');
            $table->string('receipt');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cons_items');
    }
};