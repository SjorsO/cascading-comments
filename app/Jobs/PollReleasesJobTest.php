<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PollReleasesJobTest extends TestCase
{
    /** @test */
    function it_stores_releases_in_the_database()
    {
        Http::fakeSequence()
            ->pushFile(base_path('tests/Fixtures/github-tags-01.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-02.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-03-empty.json'));

        $repository = Repository::factory()->create();

        $this->assertTrue($repository->next_poll_at->isPast());
        $this->assertTrue($repository->last_polled_at->isPast());

        PollReleasesJob::run();

        $this->assertTrue($repository->refresh()->next_poll_at->isFuture());
        $this->assertTrue($repository->last_polled_at->isToday());

        $this->assertCount(6, $repository->releases);

        [$release] = $repository->releases;

        $this->assertSame('5.0.30', $release->name);
        $this->assertSame('005000030', $release->order);
        $this->assertSame('c7ffbf1fd4895ac4cdac551265d635d995346d97', $release->commit_hash);
        $this->assertSame('https://codeload.github.com/laravel/framework/legacy.zip/c7ffbf1fd4895ac4cdac551265d635d995346d97', $release->download_url);
        $this->assertSame('2015-05-08 19:11:13', $release->published_at->toDateTimeString());
        $this->assertFalse($release->is_processed);
    }

    /** @test */
    function it_ignores_existing_releases()
    {
        $httpFakeSequence = Http::fakeSequence()
            ->pushFile(base_path('tests/Fixtures/github-tags-01.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-03-empty.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-01.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-03-empty.json'));

        $repository = Repository::factory()->create();

        PollReleasesJob::run();

        $this->assertCount(3, $repository->releases);

        $this->travel(3)->days();

        PollReleasesJob::run();

        $this->assertCount(3, $repository->refresh()->releases);
        $this->assertTrue($repository->next_poll_at->isFuture());

        $this->assertTrue($httpFakeSequence->isEmpty());
    }
}
