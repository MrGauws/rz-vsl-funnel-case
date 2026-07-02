<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('marketplace', 40);
            $table->string('transaction_id', 120);
            $table->string('email');
            $table->string('product_code', 80);
            $table->unsignedInteger('amount_cents');
            $table->char('currency', 3)->default('USD');
            $table->string('status', 40)->index();
            $table->string('affiliate_id', 80)->nullable();
            $table->string('click_id', 120)->nullable();
            $table->json('raw_payload');
            $table->timestamps();
            $table->unique(['marketplace', 'transaction_id']);
            $table->index('email');
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

