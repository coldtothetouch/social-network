<?php

use App\Models\Post;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('post_reactions', function (Blueprint $table) {
            $table->dropForeignIdFor(Post::class);
            $table->dropColumn('post_id');
            $table->rename('reactions');
        });

        Schema::table('reactions', function (Blueprint $table) {
            $table->morphs('reactionable');
        });
    }

    public function down(): void
    {
        Schema::table('reactions', function (Blueprint $table) {
            $table->dropMorphs('reactionable');
            $table->rename('post_reactions');
        });

        Schema::table('post_reactions', function (Blueprint $table) {
            $table->foreignIdFor(Post::class)->constrained();
        });
    }
};
