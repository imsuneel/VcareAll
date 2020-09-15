<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('wife_name')->nullable();
            $table->string('child_name')->nullable();
            $table->text('address')->nullable();
            $table->string('country_id');
            $table->string('state_id');
            $table->string('city_id');
            $table->string('zip_code','6');
            $table->timestamps();

            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_details');
    }
}
