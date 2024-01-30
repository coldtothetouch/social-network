<?php

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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();

            $table->string('name', 255);
            $table->string('slug', 255);
            $table->longText('description')->nullable();

            $table->string('cover_path', 1024)->nullable();
            $table->string('avatar_path', 1024)->nullable();

            $table->boolean('private', 1024)->default(false);

            $table->foreignIdFor(User::class);
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
