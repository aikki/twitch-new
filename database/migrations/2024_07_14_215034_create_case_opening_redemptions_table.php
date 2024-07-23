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
        Schema::create('case_opening_redemptions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('case_opening_id')->nullable()->constrained('case_openings')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->foreignId('reward_id')->nullable()->constrained('case_opening_rewards')->onUpdate('CASCADE')->onDelete('SET NULL');
            $table->string('twitch_user_id', 31)->nullable();
            $table->string('twitch_user_name', 255)->nullable();
            $table->string('twitch_reward_name', 255)->nullable();
            $table->integer('twitch_reward_cost')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_opening_redemptions');
    }
};
