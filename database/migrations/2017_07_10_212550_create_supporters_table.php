<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supporters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uuid')->unique();
            $table->timestamps();
            $table->string('vorname')->nullable();
            $table->string('nachname')->nullable();
            $table->string('mail')->nullable();
            $table->string('strasse')->nullable();
            $table->integer('plz')->nullable();
            $table->string('ort')->nullable();
            $table->string('land')->nullable();
            $table->float('beitrag')->nullable();
            $table->index(['uuid']);
            $table->integer('campaign_id');
            $table->foreign('campaign_id')->references('id')->on('campaigns');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('supporters');
    }
}
