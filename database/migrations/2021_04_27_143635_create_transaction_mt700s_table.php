<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionMt700sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_mt700s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transactions_id');
            $table->string("F20")->nullable();
            $table->string("F31C")->nullable();
            $table->string("F31D")->nullable();
            $table->string("F50")->nullable();
            $table->string("F59")->nullable();
            $table->string("F32B")->nullable();
            $table->string("F39A")->nullable();
            $table->string("F42A")->nullable();
            $table->string("F32B_CUR")->nullable();
            $table->string("F32B_AMT")->nullable();
            $table->string("F42C")->nullable();
            $table->string("F44C")->nullable();
            $table->string("F52A")->nullable();
            $table->string("filename")->nullable();
            $table->string("sender")->nullable();
            $table->string("receiver")->nullable();

            $table->timestamps();
        });

        Schema::table('transaction_mt700s',  function(Blueprint $table){
            $table->integer('created_by')->after('created_at')->nullable();
            $table->integer('updated_by')->after('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_mt700s');
    }
}
