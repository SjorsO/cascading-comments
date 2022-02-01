<?php

namespace App\Lcc;

use Tests\TestCase;

class FrameworkFileTest extends TestCase
{
    /** @test */
    function it_can_detect_a_comment_in_a_file()
    {
        $content = file_get_contents(
            base_path('server.php')
        );

        $frameworkFile = new FrameworkFile($content);

        $comments = $frameworkFile->comments();

        $this->assertCount(1, $comments);

        $comment = $comments[0];

        $this->assertTrue($comment->is_perfect);
        $this->assertSame(3, $comment->lines_count);

        $this->assertSame(<<<COMMENT
        This file allows us to emulate Apache's "mod_rewrite" functionality from the
        built-in PHP web server. This provides a convenient way to test a Laravel
        application without having installed a "real" web server software here.
        COMMENT, $comment->toString());
    }

    /** @test */
    function it_can_find_no_comments()
    {
        $content = file_get_contents(
            base_path('phpunit.xml')
        );

        $frameworkFile = new FrameworkFile($content);

        $this->assertCount(0, $frameworkFile->comments());
    }

    /** @test */
    function it_can_detect_multiple_comments_in_a_file()
    {
        $content = file_get_contents(
            base_path('artisan')
        );

        $frameworkFile = new FrameworkFile($content);

        $comments = $frameworkFile->comments();

        $this->assertCount(3, $comments);

        [$comment1, $comment2, $comment3] = $comments;

        $this->assertSame(<<<COMMENT
        Composer provides a convenient, automatically generated class loader
        for our application. We just need to utilize it! We'll require it
        into the script here so that we do not have to worry about the
        loading of any of our classes manually. It's great to relax.
        COMMENT, $comment1->toString());

        $this->assertSame(<<<COMMENT
        When we run the console application, the current CLI command will be
        executed in this console and the response sent back to a terminal
        or another output device for the developers. Here goes nothing!
        COMMENT, $comment2->toString());

        $this->assertSame(<<<COMMENT
        Once Artisan has finished running, we will fire off the shutdown events
        so that any final work may be done by the application before we shut
        down the process. This is the last thing to happen to the request.
        COMMENT, $comment3->toString());
    }
}
