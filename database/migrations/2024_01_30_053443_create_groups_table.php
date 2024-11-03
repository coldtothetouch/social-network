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

            $table->boolean('private')->default(false);

            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable()->constrained('users');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
