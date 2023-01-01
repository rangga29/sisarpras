<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('non_cons_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_code')->unique();
            $table->string('category_name')->unique();
            $table->string('category_slug')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('non_cons_categories');
    }
};