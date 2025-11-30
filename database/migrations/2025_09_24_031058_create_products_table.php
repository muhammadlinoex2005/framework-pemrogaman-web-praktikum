<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('unit', 50);
            $table->string('type', 50);
            $table->text('information')->nullable();
            $table->integer('qty');
            $table->timestamps(); // This creates created_at and updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
