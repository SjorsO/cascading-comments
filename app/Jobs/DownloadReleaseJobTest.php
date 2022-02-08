<?php

namespace App\Jobs;

use App\Models\Repository;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DownloadReleaseJobTest extends TestCase
{
    /** @test */
    function it_downloads_zips()
    {
        $fakeHttpSequence = Http::fakeSequence()
            ->pushFile(base_path('tests/Fixtures/github-tags-01.json'))
            ->pushFile(base_path('tests/Fixtures/github-tags-03-empty.json'))
            ->pushFile(base_path('tests/Fixtures/zips/laravel-8.6.10.zip'))
            ->pushFile(base_path('tests/Fixtures/zips/laravel-framework-5.2.41.zip'));

        $repository = Repository::factory()->create();

        PollReleasesJob::run();

        $this->assertCount(3, $repository->releases);

        [$release1, $release2, $release3] = $repository->releases;

        $release2->update(['has_downloaded_release' => true]);

        DownloadReleaseJob::run();

        $this->assertTrue($release1->refresh()->has_downloaded_release);
        Storage::assertExists($release1->zip_storage_path);

        $this->assertTrue($release3->refresh()->has_downloaded_release);
        Storage::assertExists($release3->zip_storage_path);

        $this->assertTrue($fakeHttpSequence->isEmpty());

        // All releases have been downloaded. Running the job again won't make any HTTP calls (if
        // it does, the fake sequence will fail).
        DownloadReleaseJob::run();
    }
}
