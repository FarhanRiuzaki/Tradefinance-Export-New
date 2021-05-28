<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionAdviseJurnalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_advise_jurnals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transactions_id');
            $table->date('date')->nullable();
            $table->string('type')->nullable();
            $table->string('lc_code')->nullable();
            $table->string('dc')->nullable();
            $table->string('rek')->nullable();
            $table->string('branch')->nullable();
            $table->string('description')->nullable();
            $table->string('ccy')->nullable();
            $table->string('ammount')->nullable();
            $table->string('kurs')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('transaction_advise_jurnals');
    }
}
