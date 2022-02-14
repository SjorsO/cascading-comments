<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->string('owner');
            $table->string('name');
            $table->dateTime('next_poll_at');
            $table->dateTime('last_polled_at');
            $table->timestamps();

            $table->unique(['owner', 'name']);
        });
    }
};
