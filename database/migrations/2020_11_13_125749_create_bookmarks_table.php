<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookmarksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('labels')) {
            Schema::create('bookmarks', function (Blueprint $table) {
                $table->collation = 'utf8mb4_unicode_ci';
                $table->charset = 'utf8mb4';
                $table->increments('id');
                $table->string('title', 500)->collation('utf8mb4_unicode_ci');
                $table->string('url', 500);
                $table->string('google_timestamp', 18);
                $table->string('google_id', 20);
                $table->boolean('is_valid')->default(true);
                $table->boolean('is_private')->default(false);
                $table->dateTime('added_at')->nullable();
                $table->timestamps();
            });
            //DB::statement('ALTER DATABASE bookmarks CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookmarks');
    }
}
