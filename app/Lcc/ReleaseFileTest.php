<?php

namespace App\Lcc;

use App\Lcc\Enums\CommentType;
use Tests\TestCase;

class ReleaseFileTest extends TestCase
{
    /** @test */
    function it_can_detect_a_comment_in_a_file()
    {
        $content = file_get_contents(
            base_path('server.php')
        );

        $frameworkFile = new ReleaseFile('server.php', 0, $content);

        $comments = $frameworkFile->comments;

        $this->assertCount(1, $comments);

        $comment = $comments[0];

        $this->assertTrue($comment->is_perfect);
        $this->assertSame(CommentType::SLASH_COMMENT, $comment->type);
        $this->assertSame(3, $comment->lines_count);
        $this->assertSame(13, $comment->startsAtLineNumber);

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

        $frameworkFile = new ReleaseFile('index.php', 0, $content);

        $this->assertCount(0, $frameworkFile->comments);
    }

    /** @test */
    function it_can_detect_multiple_pipe_comments_in_a_file()
    {
        $content = file_get_contents(
            base_path('artisan')
        );

        $frameworkFile = new ReleaseFile('artisan', 0, $content);

        $this->assertCount(3, $frameworkFile->comments);

        [$comment1, $comment2, $comment3] = $frameworkFile->comments;

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

    /** @test */
    function it_can_detect_lua_comments()
    {
        $content = file_get_contents(
            base_path('tests/Fixtures/files/LuaScripts.php')
        );

        $frameworkFile = new ReleaseFile('LuaScripts.php', 0, $content);

        $this->assertCount(1, $frameworkFile->comments);

        $comment = $frameworkFile->comments[0];

        $this->assertTrue($comment->is_perfect);
        $this->assertSame(CommentType::LUA_COMMENT, $comment->type);
        $this->assertSame(3, $comment->lines_count);
        $this->assertSame(110, $comment->startsAtLineNumber);

        $this->assertSame(<<<COMMENT
        If we have values in the array, we will remove them from the first queue
        and add them onto the destination queue in chunks of 100, which moves
        all of the appropriate jobs onto the destination queue very safely.
        COMMENT, $comment->toString());
    }

    /** @test */
    function it_ignores_docblocks_as_comments()
    {
        $frameworkFile = new ReleaseFile('LuaScripts.php', 0, <<<CONTENT
        class LuaScripts
        {
            /**
             * Get the Lua script for computing the size of queue.
             *
             * KEYS[1] - The name of the primary queue
             * KEYS[2] - The name of the "delayed" queue
             * KEYS[3] - The name of the "reserved" queue
             *
             * @return string
             */
            public static function size()
            {
                    return <<<'LUA'
                    return redis.call('llen', KEYS[1]) + redis.call('zcard', KEYS[2]) + redis.call('zcard', KEYS[3])
                    LUA;
            }
        }
        CONTENT);

        $this->assertCount(0, $frameworkFile->comments);
    }

    /** @test */
    function it_ignores_docblocks_that_dont_have_an_empty_line_before_return()
    {
        $frameworkFile = new ReleaseFile('Translator.php', 0, <<<CONTENT
        /**
         * Get the translation for the given key.
         *
         * @param  string  \$key
         * @param  array  \$replace
         * @param  string|null  \$locale
         * @param  bool  \$fallback
         * @return string|array
         */
        public function get()
        {
            //
        }
        CONTENT);

        $this->assertCount(0, $frameworkFile->comments);
    }

    /** @test */
    function it_correctly_processes_tricky_files()
    {
        $content = file_get_contents(
            base_path('tests/Fixtures/files/Gate.php')
        );

        $frameworkFile = new ReleaseFile('Gate.php', 0, $content);

        $this->assertCount(5, $frameworkFile->comments);
        $this->assertSame(422, $frameworkFile->comments[0]->startsAtLineNumber);
        $this->assertSame(433, $frameworkFile->comments[1]->startsAtLineNumber);
        $this->assertSame(735, $frameworkFile->comments[2]->startsAtLineNumber);
        $this->assertSame(742, $frameworkFile->comments[3]->startsAtLineNumber);
        $this->assertSame(786, $frameworkFile->comments[4]->startsAtLineNumber);
    }
}
