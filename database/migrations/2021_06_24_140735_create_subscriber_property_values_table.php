<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriberPropertyValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriber_property_values', function (Blueprint $table) {
            $table->v_id();
            $table->unsignedBigInteger('subscriber_id');
            $table->string('org_id', 15);
            $table->unsignedBigInteger('subscriber_property_id');
            $table->string('value', 150);
            $table->timestamps();

            $table->foreign('subscriber_id')->references('id')->on('subscribers');
            $table->foreign('subscriber_property_id')->references('id')->on('subscriber_properties');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriber_property_values');
    }
}
