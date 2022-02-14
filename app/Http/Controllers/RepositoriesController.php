<?php

namespace App\Http\Controllers;

use App\Models\Repository;

class RepositoriesController
{
    public function show($owner, $name)
    {
        $repository = Repository::findByOwnerAndName($owner, $name);

        return view('repository', [
            'repository' => $repository,
        ]);
    }
}
