<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->tinyInteger('discount_percent')->default(20);
            $table->date('expires_at')->nullable();
            $table->boolean('is_used')->default(false);
            $table->string('used_by_phone')->nullable();
            $table->string('used_by_email')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_codes');
    }
};