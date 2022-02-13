<div>
    <x-inputs.select :options="$commentOptions" onchange="Livewire.emit('selectedCommentChanged', this.value)" label="Comment"/>

    <livewire:comment-view :comment="$this->comment"/>
</div>
