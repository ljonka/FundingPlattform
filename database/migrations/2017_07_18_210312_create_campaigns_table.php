<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampaignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('uuid')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('repeated_campaign')->default(true);
            $table->integer('repeat_interval')->default('30');
            $table->float('amount');
            $table->integer('owner')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaigns');
    }
}
