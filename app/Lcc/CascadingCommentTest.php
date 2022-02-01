<?php

namespace App\Lcc;

use Tests\TestCase;

class CascadingCommentTest extends TestCase
{
    /**
     * @test
     * @dataProvider providesPerfectComments
     */
    function it_recognizes_a_perfect_comment($string)
    {
        $cascadingComment = new CascadingComment($string);

        $this->assertTrue($cascadingComment->is_perfect);
    }

    /**
     * @test
     * @dataProvider providesImperfectComments
     */
    function it_recognizes_an_imperfect_comment($string)
    {
        $cascadingComment = new CascadingComment($string);

        $this->assertFalse($cascadingComment->is_perfect);
    }

    public function providesPerfectComments()
    {
        return array_map(fn ($string) => [$string], [
            <<<COMMENT
            Composer provides a convenient, automatically generated class loader
            for our application. We just need to utilize it! We'll require it
            into the script here so that we do not have to worry about the
            loading of any of our classes manually. It's great to relax.
            COMMENT,
            <<<COMMENT
            When we run the console application, the current CLI command will be
            executed in this console and the response sent back to a terminal
            or another output device for the developers. Here goes nothing!
            COMMENT,
            <<<COMMENT
            Once Artisan has finished running, we will fire off the shutdown events
            so that any final work may be done by the application before we shut
            down the process. This is the last thing to happen to the request.
            COMMENT,
        ]);
    }

    public function providesImperfectComments()
    {
        return array_map(fn ($string) => [$string], [
            <<<COMMENT
            aaaaaaaaa
            bbbbbbb
            ccc.
            COMMENT,
            <<<COMMENT
            aaaaaaaaa
            bbbbb
            cc.
            COMMENT,
            <<<COMMENT
            aaaaaaaa
            bbbbb
            ccc
            COMMENT,
        ]);
    }
}
