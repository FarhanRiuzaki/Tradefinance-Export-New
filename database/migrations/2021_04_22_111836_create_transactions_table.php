<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // data dari mt700
            $table->string('code')->nullable()->comment("Nomer Registrasi");
            $table->string('source')->nullable()->comment('Sumber Data');
            $table->string('lc_code')->nullable()->comment('No LC');
            $table->double('amount', 22,2)->nullable();
            $table->double('amount_limit', 22,2)->nullable()->comment('Persentase');
            $table->string('beneficiary');
            $table->string('sender');
            $table->string('currency');

            // data input master
            $table->string('cif');
            $table->string('branch_code');
            $table->string('phone');
            $table->string('npwp');
            $table->enum('lc_purpose', ['L/C Luar Negeri', 'L/C Dalam Negeri']);
            $table->enum('lc_type', ['Sight L/C', 'Usance L/C', 'Acceptance L/C', 'Negotiation L/C']);
            $table->enum('facility_type', ['Collection', 'NWE']);
            $table->string('rek_payment')->nullable()->comment('Rekening Biaya');
            $table->string('rek_export')->comment('Rekening Hasil Export');
            $table->string('maker_note')->nullable()->comment('Notification Letter');

            // untuk flaging
            $table->unsignedBigInteger('flag_id')->nullable();
            $table->text('flag_data')->nullable();

            // status approve
            $table->date('approve_date')->nullable();
            $table->integer('approve_by')->nullable();
            $table->date('amend_approve_date')->nullable();
            $table->integer('counter_revisi');
            $table->string('approve_note')->nullable()->comment("Catatan Approval");

            $table->string('charges_currency_a');
            $table->string('charges_amount_a');
            $table->string('charges_currency_b');
            $table->string('charges_amount_b');

            $table->timestamps();
        });

        Schema::table('transactions',  function(Blueprint $table){
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
        Schema::dropIfExists('transactions');
    }
}
