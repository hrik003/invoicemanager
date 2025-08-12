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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->nullable();
            $table->unsignedBigInteger('user_id')->foreignId()->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('customer_id')->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('issue_date')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['draft','issued','paid','cancelled'])->default('draft');
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->string('currency', 10)->default('INR');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
