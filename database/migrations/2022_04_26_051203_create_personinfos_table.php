<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personinfos', function (Blueprint $table) {
            $table->increments('person_id');
            $table->string('name_name');
            $table->string('designation');
            $table->string('email');
            $table->longText('gender');
            $table->longText('person_status');
            $table->longText('address_line1');
            $table->longText('address_line2');
            $table->longText('city');
            $table->longText('country_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personinfos');
    }
};
