<?php

namespace App\Lcc\Github\Records;

use Carbon\Carbon;

class TagRecord
{
    public string $name;

    public string $nameForOrdering;

    public string $commitHash;

    public string $downloadUrl;

    public Carbon $publishedAt;

    public function __construct($data)
    {
        $this->name = $data['node']['name'];

        $this->nameForOrdering = $this->formatReleaseName($this->name);

        $this->publishedAt = Carbon::parse($data['node']['target']['committedDate']);

        $this->commitHash = $data['node']['target']['oid'];

        $this->downloadUrl = $data['node']['target']['zipballUrl'];
    }

    /**
     * Release names are version numbers. They look like this:
     *   v6.3.0
     *   5.3
     *   9.0.0-beta.2
     *   v3.0.0-rc-2
     *
     * We want to sort the releases by version number, but version numbers can't be sorted the normal
     * way (at least, not without a complicated query). For example, version "6.20.0" is newer than
     * "6.3.0", but since 3 is bigger than 2 they'll end up in the wrong order. To solve this, instead
     * of writing a black magic SQL query, we store a reformatted value that can be sorted normally.
     */
    private function formatReleaseName($name)
    {
        $name = ltrim($name, 'v');

        [$version, $label] = str_contains($name, '-')
            ? explode('-', $name, 2)
            : [$name, ''];

        $versionParts = explode('.', $version);

        if (count($versionParts) === 2) {
            $versionParts[] = 0;
        }

        throw_if(count($versionParts) !== 3, 'Unsupported version name: '.$name);

        [$major, $minor, $patch] = $versionParts;

        return sprintf(
            '%s%s%s%s',
            str_pad($major, 3, '0', STR_PAD_LEFT),
            str_pad($minor, 3, '0', STR_PAD_LEFT),
            str_pad($patch, 3, '0', STR_PAD_LEFT),
            $label ? '-'.$label : '',
        );
    }
}
