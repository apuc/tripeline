<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('name', 256);
            $table->string('email', 256)->nullable();
            $table->string('represent_first_name', 256)->nullable();
            $table->string('represent_last_name', 256)->nullable();
            $table->string('represent_country', 256)->nullable();
            $table->date('represent_date_of')->nullable();
            $table->string('address_country')->nullable();
            $table->string('address_city')->nullable();
            $table->string('address_address', 256)->nullable();
            $table->string('address_postal_code', 256)->nullable();
            $table->string('head_country')->nullable();
            $table->string('head_city')->nullable();
            $table->string('head_address', 256)->nullable();
            $table->string('head_postal_code', 256)->nullable();
            $table->string('billing_country')->nullable();
            $table->string('billing_company')->nullable();
            $table->string('vat', 256)->nullable();
            $table->string('iban', 256)->nullable();
            $table->string('licence', 256)->nullable();
            $table->string('certified', 256)->nullable();

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
        Schema::dropIfExists('companies');
    }
}
