<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('funnel_events', function (Blueprint $table): void {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->string('event_name', 80)->index();
            $table->string('path');
            $table->string('variant', 16)->nullable();
            $table->string('affiliate_id', 80)->nullable();
            $table->string('click_id', 120)->nullable();
            $table->string('campaign', 120)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['session_id', 'created_at']);
            $table->index(['event_name', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funnel_events');
    }
};

