<?php

namespace Juzaweb\Translation\Actions;

use Juzaweb\Abstracts\Action;
use Juzaweb\Facades\HookAction;

class MainAction extends Action
{
    /**
     * Execute the actions.
     *
     * @return void
     */
    public function handle()
    {
        $this->addAction(Action::BACKEND_CALL_ACTION, [$this, 'addBackendMenu']);
    }

    public function addBackendMenu()
    {
        HookAction::addAdminMenu(
            trans('juzaweb::app.translations'),
            'translations',
            [
                'icon' => 'fa fa-language',
                'position' => 90,
            ]
        );
    }
}
