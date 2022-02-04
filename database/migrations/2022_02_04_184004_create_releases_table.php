<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReleasesTable extends Migration
{
    public function up()
    {
        Schema::create('releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('repository_id')->constrained()->cascadeOnDelete();
            $table->string('name')->unique();
            $table->string('formatted_name');
            $table->string('commit_hash')->unique();
            $table->string('download_url');
            $table->dateTime('published_at');
            $table->boolean('is_processed')->default(false);
            $table->boolean('has_downloaded_release')->default(false);
            $table->timestamps();
        });
    }
}
