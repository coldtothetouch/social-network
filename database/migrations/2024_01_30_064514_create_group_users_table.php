<?php

use App\Models\Group;
use App\Models\User;
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
        Schema::create('group_users', function (Blueprint $table) {
            $table->id();

            // token for admin to invite users
            $table->string('token', 1024)->nullable();
            $table->timestamp('token_date_of_use')->nullable();
            $table->timestamp('token_expiration_date')->nullable();

            $table->string('role', 25);
            $table->string('status', 25); // pending | approved

            $table->foreignIdFor(User::class);
            $table->foreignIdFor(Group::class);
            $table->foreignIdFor(User::class, 'created_by')->nullable();

            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_users');
    }
};
