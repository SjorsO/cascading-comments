<?php

namespace App\Lcc\Github;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GithubApiTest extends TestCase
{
    /** @test */
    function it_can_get_releases_from_the_github_api()
    {
        Http::fakeSequence()
            ->pushFile(base_path('tests/Fixtures/github-tags-01.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-02.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-03-empty.json'));

        $releases = (new GithubApi)->getReleases('laravel', 'framework');

        $this->assertCount(6, $releases);

        $this->assertSame('5.0.30', $releases[0]->name);
        $this->assertSame('v5.0.30', $releases[0]->formattedName);
        $this->assertSame('c7ffbf1fd4895ac4cdac551265d635d995346d97', $releases[0]->commitHash);
        $this->assertSame('https://codeload.github.com/laravel/framework/legacy.zip/c7ffbf1fd4895ac4cdac551265d635d995346d97', $releases[0]->downloadUrl);
        $this->assertSame('2015-05-08 19:11:13', $releases[0]->publishedAt->toDateTimeString());
    }
}
