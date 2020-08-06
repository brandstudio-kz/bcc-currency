<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->string('title')->unique();
            $table->date('pub_date')->nullable();
            $table->double('value', 8, 2)->nullable();
            $table->double('description', 8, 2)->nullable()->default(1);
            $table->integer('quant')->nullable();
            $table->string('index')->nullable();
            $table->double('change', 8, 2)->nullable()->default(0);

            $table->tinyInteger('status')->default(config('currency.currency_class')::DRAFT);

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('lft')->unsigned()->nullable();
            $table->integer('rgt')->unsigned()->nullable();
            $table->integer('depth')->unsigned()->nullable();

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
        Schema::dropIfExists('currencies');
    }
}
