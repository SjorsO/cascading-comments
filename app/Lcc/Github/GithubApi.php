<?php

namespace App\Lcc\Github;

use App\Lcc\Github\Records\TagRecord;
use Illuminate\Support\Facades\Http;

/**
 * https://developer.github.com/v4/explorer/
 */
class GithubApi
{
    private $token;

    public function __construct()
    {
        $this->token = config('lcc.github_personal_access_token');
    }

    public function getReleases($owner, $name)
    {
        $releases = [];

        $cursor = null;

        while (true) {
            $response = $this->query(<<<QUERY
            query {
                repository(owner: "$owner", name: "$name") {
                    refs(
                        refPrefix: "refs/tags/"
                        first: 100
                        $cursor
                    ) {
                        edges {
                            cursor
                            node {
                                name
                                target {
                                    ... on Commit {
                                        id
                                        committedDate
                                        oid
                                        tarballUrl
                                    }
                                }
                            }
                        }
                    }
                }
            }
            QUERY);

            $edges = $response->json('data.repository.refs.edges');

            if (! $edges) {
                break;
            }

            foreach ($edges as $edge) {
                $releases[] = new TagRecord($edge);
            }

            $cursor = sprintf('after: "%s"', array_pop($edges)['cursor']);
        }

        return $releases;
    }

    private function query($query)
    {
        return Http::withToken($this->token)
            ->retry(2, 5000)
            ->timeout(5)
            ->post('https://api.github.com/graphql', [
                'query' => $query,
            ]);
    }
}
