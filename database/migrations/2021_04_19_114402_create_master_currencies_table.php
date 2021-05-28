<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('description');
            $table->string('bi_id')->nullable();
            $table->string('gl_code')->nullable();
            $table->timestamps();
        });

        Schema::table('master_currencies',  function(Blueprint $table){
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
        Schema::dropIfExists('master_currencies');
    }
}
