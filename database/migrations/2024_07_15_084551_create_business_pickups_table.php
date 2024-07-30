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
        Schema::create('business_pickups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requestor_branch_id')->constrained('branches')->onDelete('cascade');
            $table->foreignId('collector_branch_id')->constrained('branches')->onDelete('cascade');
            $table->json('waste_payload');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('remark')->nullable();
            $table->dateTime('requested_at');
            $table->dateTime('request_pickup_time');
            $table->dateTime('accepted_rejected_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('contact_number', 15);
            $table->string('street');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();

            // Indexing columns for faster queries
            $table->index('requestor_branch_id');
            $table->index('collector_branch_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_pickups');
    }
};
