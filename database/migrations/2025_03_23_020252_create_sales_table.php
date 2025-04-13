<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // รหัสการขาย
            $table->unsignedBigInteger('customer_id'); // รหัสลูกค้า
            $table->string('invoice_number')->nullable(); // หมายเลขใบเสร็จ
            $table->enum('payment_method', ['cash', 'credit']); // วิธีการชำระเงิน (เงินสด, เครดิต)
            $table->decimal('total', 10, 2); // ยอดรวม
            $table->date('due_date'); // วันที่กำหนดชำระ
            $table->timestamps(); // วันที่สร้างและอัพเดต
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
