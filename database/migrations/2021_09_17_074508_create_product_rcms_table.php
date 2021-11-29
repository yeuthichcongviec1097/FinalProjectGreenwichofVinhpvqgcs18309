<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductRcmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_rcms', function (Blueprint $table) {
            $table->id();
            $table->integer('idProductRcm0');
            $table->integer('idProductRcm1');
            $table->integer('idProductRcm2');
            $table->integer('idProductRcm3');
            $table->integer('idProductRcm4');
            $table->integer('idProductRcm5');
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
        Schema::dropIfExists('product_rcms');
    }
}
