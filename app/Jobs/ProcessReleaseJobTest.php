<?php

namespace App\Jobs;

use App\Lcc\Enums\CommentType;
use Tests\Support\CreatesModels;
use Tests\TestCase;

class ProcessReleaseJobTest extends TestCase
{
    /** @test */
    function it_stores_comments_in_the_database()
    {
        $release = CreatesModels::release(
            base_path('tests/Fixtures/zips/test-release.zip')
        );

        ProcessReleaseJob::dispatch($release);

        $this->assertCount(3, $release->comments);

        [$comment1, $comment2, $comment3] = $release->comments;

        $this->assertSame('artisan', $comment1->file_path);
        $this->assertSame(CommentType::PIPE_COMMENT, $comment1->type);
        $this->assertSame(1, $comment1->zip_index);
        $this->assertSame(4, $comment1->number_of_lines);
        $this->assertSame(10, $comment1->starts_at_line_number);
        $this->assertTrue($comment1->is_perfect);

        $this->assertSame('artisan', $comment2->file_path);
        $this->assertSame(CommentType::PIPE_COMMENT, $comment2->type);
        $this->assertSame(1, $comment2->zip_index);
        $this->assertSame(3, $comment2->number_of_lines);
        $this->assertSame(24, $comment2->starts_at_line_number);
        $this->assertTrue($comment2->is_perfect);

        $this->assertSame('public/index.php', $comment3->file_path);
        $this->assertSame(CommentType::PIPE_COMMENT, $comment3->type);
        $this->assertSame(3, $comment3->zip_index);
        $this->assertSame(3, $comment3->number_of_lines);
        $this->assertSame(12, $comment3->starts_at_line_number);
        $this->assertTrue($comment3->is_perfect);
    }

    /** @test */
    function it_can_process_a_real_skeleton_release()
    {
        $release = CreatesModels::release(
            base_path('tests/Fixtures/zips/laravel-8.6.10.zip')
        );

        ProcessReleaseJob::dispatch($release);

        $this->assertSame(91, $release->comments->count());
        $this->assertSame(86, $release->comments->where('is_perfect', true)->count());

        $this->assertSame(1, $release->comments->where('type', CommentType::SLASH_COMMENT)->count());
        $this->assertSame(0, $release->comments->where('type', CommentType::LUA_COMMENT)->count());
        $this->assertSame(2, $release->comments->where('type', CommentType::MULTILINE_COMMENT)->count());
        $this->assertSame(88, $release->comments->where('type', CommentType::PIPE_COMMENT)->count());

        $this->assertSame(3, $release->comments->min('number_of_lines'));
        $this->assertSame(4, $release->comments->max('number_of_lines'));

        // This file contains commented javascript that kind of looks like a cascading comment.
        $this->assertCount(2, $bootstrapComments = $release->comments->where('file_path', 'resources/js/bootstrap.js'));
        $this->assertSame(3, $bootstrapComments->first()->starts_at_line_number);
        $this->assertSame(13, $bootstrapComments->last()->starts_at_line_number);
    }

    /** @test */
    function it_can_process_a_real_framework_release()
    {
        $release = CreatesModels::release(
            base_path('tests/Fixtures/zips/laravel-framework-5.2.41.zip')
        );

        ProcessReleaseJob::dispatch($release);

        $this->assertSame(522, $release->comments->count());
        $this->assertSame(433, $release->comments->where('is_perfect', true)->count());

        $this->assertSame(521, $release->comments->where('type', CommentType::SLASH_COMMENT)->count());
        $this->assertSame(0, $release->comments->where('type', CommentType::LUA_COMMENT)->count());
        $this->assertSame(0, $release->comments->where('type', CommentType::MULTILINE_COMMENT)->count());
        $this->assertSame(1, $release->comments->where('type', CommentType::PIPE_COMMENT)->count());

        $this->assertSame(3, $release->comments->min('number_of_lines'));
        $this->assertSame(4, $release->comments->max('number_of_lines'));
    }

    /** @test */
    function it_can_process_a_another_real_framework_release()
    {
        $release = CreatesModels::release(
            base_path('tests/Fixtures/zips/laravel-framework-9.0.2.zip')
        );

        ProcessReleaseJob::dispatch($release);

        $this->assertSame(579, $release->comments->count());
        $this->assertSame(509, $release->comments->where('is_perfect', true)->count());

        $this->assertSame(577, $release->comments->where('type', CommentType::SLASH_COMMENT)->count());
        $this->assertSame(1, $release->comments->where('type', CommentType::LUA_COMMENT)->count());
        $this->assertSame(0, $release->comments->where('type', CommentType::MULTILINE_COMMENT)->count());
        $this->assertSame(1, $release->comments->where('type', CommentType::PIPE_COMMENT)->count());

        $this->assertSame(3, $release->comments->min('number_of_lines'));
        $this->assertSame(4, $release->comments->max('number_of_lines'));
    }
}
