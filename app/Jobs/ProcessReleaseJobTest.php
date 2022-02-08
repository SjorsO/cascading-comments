<?php

namespace App\Jobs;

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
        $this->assertSame(1, $comment1->zip_index);
        $this->assertSame(4, $comment1->number_of_lines);
        $this->assertSame(10, $comment1->starts_at_line_number);
        $this->assertTrue($comment1->is_perfect);

        $this->assertSame('artisan', $comment2->file_path);
        $this->assertSame(1, $comment2->zip_index);
        $this->assertSame(3, $comment2->number_of_lines);
        $this->assertSame(24, $comment2->starts_at_line_number);
        $this->assertTrue($comment2->is_perfect);

        $this->assertSame('public/index.php', $comment3->file_path);
        $this->assertSame(3, $comment3->zip_index);
        $this->assertSame(3, $comment3->number_of_lines);
        $this->assertSame(12, $comment3->starts_at_line_number);
        $this->assertTrue($comment3->is_perfect);
    }
}
