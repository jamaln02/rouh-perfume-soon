<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('survey_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->nullable()->unique();
            $table->tinyInteger('age');
            $table->enum('gender', ['male', 'female']);
            $table->string('location');
            
            $table->enum('perfume_quality', ['original', 'tarkib', 'both']);
            $table->text('favorite_perfumes')->nullable();
            $table->enum('purchase_frequency', ['monthly', 'quarterly', 'biannually', 'yearly', 'rarely'])->nullable();
            $table->text('main_problem')->nullable();
            $table->string('price_range')->nullable();
            $table->enum('perfume_type', ['oriental', 'western', 'mixed', 'other'])->nullable();
            $table->text('perfume_type_other')->nullable();
            $table->enum('discovery_method', ['social', 'friends', 'store', 'influencers', 'ads'])->nullable();
            $table->text('wishlist')->nullable();
            
            $table->string('discount_code')->nullable();
            $table->boolean('is_used')->default(false);
            $table->string('ip_address')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('survey_submissions');
    }
};