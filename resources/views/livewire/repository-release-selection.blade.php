<div>

    <x-inputs.select :options="$repositoryOptions" wire:model="repository_id" label="Repository"/>

    <x-inputs.select :options="$releaseOptions" onchange="Livewire.emit('selectedReleaseChanged', this.value)" label="Release"/>

    <livewire:comment-selection :release="$this->release"/>

</div>
