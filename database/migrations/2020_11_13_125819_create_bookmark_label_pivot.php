<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BookmarkLabel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('bookmark_label')) {
            Schema::create('bookmark_label', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('bookmark_id');
                $table->integer('label_id');
                $table->timestamps();
                $table->unique(['bookmark_id', 'label_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookmark_label', function (Blueprint $table) {
            $table->dropUnique(['bookmark_id', 'label_id']); // Drops index 'geo_state_index'
        });
        Schema::drop('bookmark_label');
    }
}
