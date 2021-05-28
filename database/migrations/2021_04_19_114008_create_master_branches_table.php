<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_branches', function (Blueprint $table) {
            $table->id();
            $table->string('code')->comment('000, 808');
            $table->string('branch')->comment('MBM');
            $table->string('name');
            $table->text('address');
            $table->string('tax')->nullable();
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->timestamps();
        });

        Schema::table('master_branches',  function(Blueprint $table){
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
        Schema::dropIfExists('master_branches');
    }
}
