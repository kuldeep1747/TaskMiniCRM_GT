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
        Schema::create('contact_merge_histories', function (Blueprint $table) {
            $table->id();
             $table->foreignId('master_contact_id')
                ->constrained('contacts')
                ->onDelete('cascade');

            $table->foreignId('merged_contact_id')
                ->constrained('contacts')
                ->onDelete('cascade');

            $table->timestamp('merged_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_merge_histories');
    }
};
