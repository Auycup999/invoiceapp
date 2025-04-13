<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // รหัสสินค้า
            $table->string('barcode')->unique(); // barcode
            $table->string('name'); // ชื่อสินค้า
            $table->decimal('price', 10, 2); // ราคา
            $table->string('unit'); // หน่วย
            $table->timestamps(); // timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
