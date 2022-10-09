<?php

namespace MyMicroBlog\Framework;

/**
 * Abstract Action Controller.
 * - Takes request, evaluates which corresponding action should be called
 * - Calls matched action
 * - Checks access permission for action before executing
 *
 * @author Jakob Osterberger
 * @date 10.06.2022
 */
abstract class BaseController
{
    protected string $reqAction = '';

    public function __construct()
    {
        if (isset($_REQUEST['action'])) {
            $this->reqAction = $_REQUEST['action'];
        }
        // Initialize controller
        $this->initialize();

        // execute hook 'beforeDispatch'
        if (method_exists($this, 'beforeDispatch')) {
            $this->beforeDispatch();
        }

        // execute 'hook' canAccessDispatch
        if ($this->canAccessDispatch()) {
            // Select controller action corresponding to request action and execute it
            $this->dispatch();
        }

        // execute hook 'afterDispatch'
        if (method_exists($this, 'afterDispatch')) {
            $this->afterDispatch();
        }
    }

    // Executes controller action matching request action
    // If no action defined executes defaultAction
    public function dispatch($actionFnName = ''): void
    {
        $actionFunctionName = $actionFnName ?: $this->reqAction . "Action"; // e.q. 'list' -> 'listAction'
        if (in_array($actionFunctionName, get_class_methods($this), true)) {
            $this->{$actionFunctionName}();
        } // If no action matches $action might be from another controller or empty
        else if (method_exists($this, 'defaultAction')) {
            $this->defaultAction();
        }
        // DO Nothing
        // echo "Error: '$actionFunctionName' for $this->action does not exist in " . get_class($this);
    }

    /**
     * Overwrite to handle access limitations
     * @return bool if dispatch is allowed
     */
    protected function canAccessDispatch(): bool
    {
        return true;
    }

    /**
     * Initialize controller variables here
     * @return void
     */
    abstract protected function initialize(): void;
}
