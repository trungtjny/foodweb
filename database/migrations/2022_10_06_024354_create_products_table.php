<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');// dang mucj
            $table->foreign("category_id")->references('id')->on('categories');
            $table->string('name');
            $table->string('thumb');//anh
            $table->integer('price')->default(99999);
            $table->integer('sold')->default(0);//da ban
            $table->text('description')->nullable();// thoong tin co ban
            $table->longtext('product_content')->nullable();//chi tiet
            $table->tinyInteger('is_show')->default(1);//hien thi
            $table->json('options')->nullable();
            $table->tinyInteger('has_option')->nullable();
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
