<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('customer_code')->unique(); // รหัสลูกค้า
            $table->string('customer_name'); // ชื่อลูกค้า
            $table->string('contact_person'); // ผู้ติดต่อ
            $table->text('address'); // ที่อยู่
            $table->string('phone'); // โทรศัพท์
            $table->string('type'); // ประเภท
            $table->decimal('credit_limit', 15, 2)->nullable(); // วงเงิน/เครดิต
            $table->text('notes')->nullable(); // หมายเหตุ
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
