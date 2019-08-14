<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToYksTimeFeedItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('yks_time_feed_items', function (Blueprint $table) {
            $table->foreign('entry_id')->references('id')->on('entries')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('feed_id')->references('id')->on('yks_time_feeds')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('yks_time_feed_items', function (Blueprint $table) {
            $table->dropForeign('yks_time_feed_items_entry_id_foreign');
            $table->dropForeign('yks_time_feed_items_feed_id_foreign');
        });
    }
}
