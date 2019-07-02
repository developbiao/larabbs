<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('topics', function(Blueprint $table){
            // when user_id reference users table data delete also delete topics
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });

        Schema::table('replies', function(Blueprint $table){
           // when user_id reference users table data delete also delete replies
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // when topic_id reference topics table data delete also delete replies
            $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // remove foreign key
        Schema::table('topics', function(Blueprint $table){
            $table->dropForeign(['user_id']);

        });

        Schema::table('replies', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropForeign(['topic_id']);
        });
    }
}
