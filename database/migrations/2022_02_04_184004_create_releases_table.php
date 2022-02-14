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
            $table->string('order');
            $table->char('commit_hash', 40)->unique();
            $table->string('download_url');
            $table->dateTime('published_at');
            $table->boolean('has_downloaded_release')->default(false);
            $table->boolean('is_processed')->default(false);
            $table->unsignedSmallInteger('comments_count')->nullable();
            $table->unsignedSmallInteger('perfect_comments_count')->nullable();
            $table->unsignedSmallInteger('imperfect_comments_count')->nullable();
            $table->timestamps();
        });
    }
}
