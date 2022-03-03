<?php

namespace App\Http\Controllers;

use App\Models\Repository;

class RepositoriesController
{
    public function show($owner, $name, $version = null)
    {
        $repository = Repository::findByOwnerAndName($owner, $name);

        $selectedRelease = $version
            ? $repository->releases->firstWhere('name', $version) ?? abort(to_route('repositories.show', [$owner, $name]))
            : $repository->releases->last();

        $labels = [];
        $perfectCommentsData = [];
        $imperfectCommentsData = [];
        $perfectPercentage = [];

        foreach ($repository->releases as $release) {
            $labels[] = $release->name;
            $perfectCommentsData[$release->name] = $release->perfect_comments_count;
            $imperfectCommentsData[$release->name] = $release->imperfect_comments_count;
            $perfectPercentage[$release->name] = $release->perfect_comment_percentage;
        }

        return view('repository', [
            'repository' => $repository,
            'labels' => $labels,
            'release' => $selectedRelease,
            'releaseOptions' => $repository->releases()->pluck('name', 'id'),
            'perfectCommentsData' => $perfectCommentsData,
            'imperfectCommentsData' => $imperfectCommentsData,
            'perfectPercentage' => $perfectPercentage,
        ]);
    }
}
