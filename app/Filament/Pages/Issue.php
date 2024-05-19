<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Model;

// Complete the 5 tasks here
// https://filamentphp.com/docs/3.x/forms/adding-a-form-to-a-livewire-component#adding-the-form
class Issue extends Page implements HasForms // @TASK 1a
{
    use InteractsWithForms; // @TASK 1b

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.issue';

    public ?array $primaryFormData = [];    // @TASK 2

    protected function getForms(): array
    {
        return [
            'primaryForm',
        ];
    }

    public function mount(): void
    {
        foreach ($this->getForms() as $form) {
            $this->{$form}->fill(); // @TASK 4
        }
    }

    private function primaryForm(Form $form): Form  // @TASK 3
    {
        return $form
            // ->model($this->primaryFormLoad())
            ->statePath('primaryFormData')
            ->schema([
                Section::make('Part 1')
                    ->aside()
                    ->description('Link your account.')
                    ->schema([
                        MarkdownEditor::make('comment')
                            ->label(__('Comment'))
                            ->toolbarButtons([
                                'attachFiles',
                                'blockquote',
                                'bold',
                                'heading',
                                'italic',
                                'link',
                                'orderedList',
                                'strike',
                                // 'bulletList',
                                // 'codeBlock',
                                // 'undo',
                                // 'redo',
                            ])
                            ->fileAttachmentsDisk('markdown')
                            ->fileAttachmentsVisibility('public')
                        // ->required()
                            ->placeholder('Add your comment here...'),
                    ]),
            ]);

    }

    protected function primaryFormActions($form): array
    {
        $actions[] = \Filament\Actions\Action::make('comment')
            ->label(__('Comment'))
            ->action('addComment')
            ->keyBindings(['mod+enter']);

        return $actions;
    }

    public function addComment()
    {
        $formData = $this->primaryForm->getState(); // @TASK 5
        // save $formData
        $this->primaryForm->fill();                 // empty the form

        Notification::make()
            ->success()
            ->title(__('filament-panels::pages/auth/edit-profile.notifications.saved.title'))
            ->send();

    }

    public function primaryFormSave(): Model|array|null
    {
        return $this->addComment();
    }

    public function primaryFormLoad(): Model|array|null
    {
        $this->primaryFormData['comment'] = '';

        return $this->primaryFormData;
    }

    protected function getFormActions($form): array
    {
        if (method_exists($this, "{$form}Actions")) {
            return $this->{"{$form}Actions"}($form);
        } else {
            return [
                Action::make($form.'UpdateAction')
                    ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                    ->submit($form),
            ];
        }
    }
}
