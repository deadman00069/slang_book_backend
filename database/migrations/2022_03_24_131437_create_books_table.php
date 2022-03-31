<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('genres_id');
            $table->foreign("genres_id")->references("id")->on("genres")->onUpdate("cascade")->onDelete("cascade");
            $table->string('name');
            $table->string('author');
            $table->string('short_desc');
            $table->string('book_url');
            $table->string('book_preview_url');
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
        Schema::dropIfExists('books');
    }
};
