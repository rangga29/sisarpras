<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('non_cons_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('non_cons_category_id')->constrained();
            $table->bigInteger('sub_category_code');
            $table->string('sub_category_name')->unique();
            $table->string('sub_category_slug')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('non_cons_sub_categories');
    }
};