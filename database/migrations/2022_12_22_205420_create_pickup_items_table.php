<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pickup_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cons_item_id')->constrained();
            $table->foreignId('consumer_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('pickup_code')->unique();
            $table->date('pickup_date');
            $table->bigInteger('amount');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pickup_items');
    }
};