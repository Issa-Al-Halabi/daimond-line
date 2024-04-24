<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFatoraPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fatora_payments', function (Blueprint $table) {
            $table->id();

            $table->string("payment_id")->unique();
            $table->string("transaction_number")->nullable();
            $table->string("amount");
            $table->string("terminal_id");
            $table->string("status");
            $table->text("notes")->nullable();
            $table->timestamp("creation_timestamp");

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
        Schema::dropIfExists('fatora_payments');
    }
}
