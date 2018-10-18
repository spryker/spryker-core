<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Kernel\Controller;

use Spryker\Shared\Kernel\Communication\RouteNameResolverInterface;

class RouteNameResolver implements RouteNameResolverInterface
{
    /**
     * @var string
     */
    protected $module;

    /**
     * @var string
     */
    protected $controller;

    /**
     * @var string
     */
    protected $action;

    /**
     * @param string $module
     * @param string $controller
     * @param string $action
     */
    public function __construct($module, $controller, $action)
    {
        $this->module = $module;
        $this->controller = $controller;
        $this->action = $action;
    }

    /**
     * @return string
     */
    public function resolve()
    {
        return $this->module . '/' . $this->controller . '/' . $this->action;
    }
}
