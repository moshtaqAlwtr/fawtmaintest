<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseQuotationsViewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_quotations_view', function (Blueprint $table) {
            $table->id();

            // معلومات أساسية
            $table->string('code')->unique();
            $table->string('purchase_price_number')->unique();
            $table->unsignedBigInteger('supplier_id')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('date');
            $table->integer('valid_days')->default(0);

            // العلاقات

            // الحسابات المالية
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('total_discount', 15, 2)->default(0);
            $table->decimal('total_tax', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2)->default(0);
            $table->decimal('shipping_cost', 15, 2)->default(0);

            // الخصومات والتسويات
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->enum('discount_type', ['amount', 'percentage'])->default('amount');
            $table->string('adjustment_label')->nullable();
            $table->enum('adjustment_type', ['discount', 'addition'])->nullable();
            $table->decimal('adjustment_value', 15, 2)->default(0);

            // الضرائب
            $table->enum('tax_type', ['vat', 'zero', 'exempt'])->default('vat');

            // الحالة والملاحظات
            $table->enum('status', ['active', 'inactive'])->default('active');
            // 1 = نشط، 0 = غير نشط
            $table->text('notes')->nullable();

            // التواريخ
            $table->timestamps();

            // المفاتيح الخارجية
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_quotations_view');
    }
}
