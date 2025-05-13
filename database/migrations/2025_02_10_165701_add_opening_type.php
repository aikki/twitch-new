<?php

use App\Enum\CaseOpeningType;
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
        Schema::table('case_openings', function(Blueprint $table) {
            $table->enum('type', CaseOpeningType::stringCases())->default(CaseOpeningType::CASE->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('case_openings', function(Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
