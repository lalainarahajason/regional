<?php

namespace App;

use App\Models\HooksInterface;
use App\Models\HooksAdminInterface;
use App\Models\HooksFrontInterface;

/**
 * OpsLeb
 *
 * @author Rahajason
 * @version 1.0.0
 * @since 1.0.0
 */
class App implements HooksInterface
{

    protected $actions = array();

    public function __construct($actions)
    {
        $this->actions = $actions;
    }

    /**
     * @return boolean
     */
    protected function canBeLoaded()
    {
        return true;
    }

    /**
     * Execute plugin
     */
    public function execute()
    {
        if ($this->canBeLoaded()) {
            add_action('init', [ $this,'hooks' ], 0);
        }
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Execute hooks from services
     */
    public function hooks()
    {

        foreach ($this->getActions() as $key => $action) {
            if ($action instanceof HooksAdminInterface) {
                if (is_admin()) {
                    $action->hooks();
                }
            }

            if ($action instanceof HooksFrontInterface) {
                if (!is_admin()) {
                    $action->hooks();
                }
            }

            if ($action instanceof HooksInterface) {
                $action->hooks();
            }
        }
    }
}
