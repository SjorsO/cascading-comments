<?php

namespace App\Http\Livewire;

use App\Models\Release;
use App\Models\Repository;
use Livewire\Component;

class RepositoryReleaseSelection extends Component
{
    public $repository_id;

    public $release_id;

    public function mount()
    {
        $this->repository_id = Repository::first()->id;

        $this->updatedRepositoryId();
    }

    public function getRepositoryProperty(): Repository
    {
        return Repository::find($this->repository_id);
    }

    public function getReleaseProperty(): Release
    {
        return Release::find($this->release_id);
    }

    public function updatedRepositoryId()
    {
        $this->release_id = $this->repository->releases()->latest()->first()->id;

        $this->emit('selectedReleaseChanged', $this->release_id);
    }

    public function render()
    {
        return view('livewire.repository-release-selection', [
            'repositoryOptions' => Repository::get()->pluck('display_name', 'id'),
            'releaseOptions' => $this->repository->releases()->latest('published_at')->pluck('name', 'id'),
        ]);
    }
}
