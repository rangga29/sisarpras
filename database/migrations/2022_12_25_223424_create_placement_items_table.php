<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('placement_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('non_cons_item_id')->constrained();
            $table->foreignId('room_id')->constrained();
            $table->foreignId('unit_id')->constrained();
            $table->string('placement_code')->unique();
            $table->unsignedBigInteger('con_placement_id');
            $table->foreign('con_placement_id')->references('id')->on('non_cons_conditions');
            $table->unsignedBigInteger('con_return_id')->nullable();
            $table->foreign('con_return_id')->references('id')->on('non_cons_conditions');
            $table->date('placement_date');
            $table->date('return_date')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('placement_items');
    }
};