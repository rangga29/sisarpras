<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('non_cons_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('non_cons_sub_category_id')->constrained();
            $table->foreignId('brand_id')->constrained();
            $table->foreignId('shop_id')->constrained();
            $table->foreignId('fund_id')->constrained();
            $table->foreignId('room_id')->constrained();
            $table->foreignId('non_cons_condition_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('item_code')->unique();
            $table->string('item_number');
            $table->string('name');
            $table->bigInteger('price');
            $table->date('purchase_date');
            $table->text('include')->nullable();
            $table->string('image');
            $table->string('receipt');
            $table->text('description')->nullable();
            $table->boolean('availability')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('non_cons_items');
    }
};