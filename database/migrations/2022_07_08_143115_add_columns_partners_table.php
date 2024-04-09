<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsPartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->string('company_name', 256);
            $table->string('company_type', 256);
            $table->string('city', 256);
            $table->string('english_lvl', 256);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partners', function($table) {
            $table->dropColumn('company_name');
            $table->dropColumn('company_type');
            $table->dropColumn('city');
            $table->dropColumn('english_lvl');
        });
    }
}
