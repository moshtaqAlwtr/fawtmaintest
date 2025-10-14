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
        Schema::table('notifications', function (Blueprint $table) {
            // Check if columns exist before adding them
            if (!Schema::hasColumn('notifications', 'receiver_id')) {
                $table->unsignedBigInteger('receiver_id')->nullable()->after('user_id');
                $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('notifications', 'read')) {
                $table->boolean('read')->default(false)->after('description');
            }
            
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->nullable()->after('read');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (Schema::hasColumn('notifications', 'receiver_id')) {
                $table->dropForeign(['receiver_id']);
                $table->dropColumn('receiver_id');
            }
            
            if (Schema::hasColumn('notifications', 'read')) {
                $table->dropColumn('read');
            }
            
            if (Schema::hasColumn('notifications', 'type')) {
                $table->dropColumn('type');
            }
        });
    }
};