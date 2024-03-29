<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->string('sender_id');
            $table->string('receiver_id');
            $table->longText('message');
            $table->datetime('sent_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->primary(['sender_id', 'receiver_id', 'sent_at']);
            $table->foreign('sender_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
            $table->foreign('receiver_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
