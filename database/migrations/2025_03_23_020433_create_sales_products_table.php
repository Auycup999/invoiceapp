<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_products', function (Blueprint $table) {
            $table->id(); // รหัสการเชื่อมโยง
            $table->unsignedBigInteger('sale_id'); // รหัสการขาย
            $table->unsignedBigInteger('product_id'); // รหัสสินค้า
            $table->integer('customer_id'); // รหัสลูกค้า
            $table->string('payment_method'); // รหัสรูปแบบการชำระ
            $table->decimal('total', 10, 2); // ยอดรวม
            $table->integer('quantity'); // จำนวนสินค้า
            $table->decimal('price', 10, 2); // ราคาของสินค้า
            $table->timestamps(); // วันที่สร้างและอัพเดตข้อมูล

            // การตั้งค่า foreign key
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_products');
    }
}
