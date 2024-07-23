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
        Schema::create('case_openings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('name', 255);
            $table->string('streamerbot_reward_id', 255);
            $table->string('view_key', 255);
            $table->timestamps();

        });
        Schema::create('case_opening_rewards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('case_opening_id')->constrained('case_openings');
            $table->string('name', 255);
            $table->integer('weight');
            $table->string('image_url',255)->nullable();
            $table->string('color',31)->nullable();
            $table->string('streamerbot_action_id', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_opening_rewards');
        Schema::dropIfExists('case_opening');
    }
};
