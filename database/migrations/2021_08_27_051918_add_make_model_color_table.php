<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMakeModelColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->integer('make_id')->after('id')->nullable();
            $table->integer('model_id')->after('make_id')->nullable();
            $table->integer('color_id')->after('model_id')->nullable();
            $table->dropColumn('make');
            $table->dropColumn('model');
            $table->dropColumn('color');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('make_id');
            $table->dropColumn('model_id');
            $table->dropColumn('color_id');
        });
    }
}
