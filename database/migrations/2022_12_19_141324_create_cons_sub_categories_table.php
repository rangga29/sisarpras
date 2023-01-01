<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cons_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cons_category_id')->constrained();
            $table->string('sub_category_name')->unique();
            $table->string('sub_category_slug')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cons_sub_categories');
    }
};