<?php

namespace App\Lcc\Github\Records;

use Carbon\Carbon;

class TagRecord
{
    public string $name;

    public string $formattedName;

    public string $commitHash;

    public string $downloadUrl;

    public Carbon $publishedAt;

    public function __construct($data)
    {
        $this->name = $data['node']['name'];

        $this->formattedName = str_starts_with($this->name, 'v')
            ? $this->name
            : 'v'.$this->name;

        $this->publishedAt = Carbon::parse($data['node']['target']['committedDate']);

        $this->commitHash = $data['node']['target']['oid'];

        $this->downloadUrl = $data['node']['target']['zipballUrl'];
    }
}
