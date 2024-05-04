<x-filament-panels::page>

@foreach ($this->getForms() as $form)
<x-filament-panels::form wire:submit="{{ $form }}Save">
    {{ $this->$form }}

    <x-filament-panels::form.actions :actions="$this->getFormActions($form)" />
</x-filament-panels::form>
@endforeach

</x-filament-panels::page>
