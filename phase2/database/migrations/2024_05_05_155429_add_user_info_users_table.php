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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->index()->after('id');
            $table->string('mobile_number')->unique()->after('email');
            $table->string('user_image')->after('mobile_number');
            $table->string('address')->after('mobile_number');
            $table->date('date_of_birth')->after('mobile_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
