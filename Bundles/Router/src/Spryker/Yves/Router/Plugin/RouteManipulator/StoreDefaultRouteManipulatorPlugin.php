<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouteManipulator;

use Spryker\Shared\Router\Route\Route;
use Spryker\Shared\RouterExtension\Dependency\Plugin\RouteManipulatorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class StoreDefaultRouteManipulatorPlugin extends AbstractPlugin implements RouteManipulatorPluginInterface
{
    /**
     * @var string
     */
    protected $allowedLocalesPattern;

    /**
     * @param string $routeName
     * @param \Spryker\Shared\Router\Route\Route $route
     *
     * @return \Spryker\Shared\Router\Route\Route
     */
    public function manipulate(string $routeName, Route $route): Route
    {
        $route->setDefault('store', 'US');

        return $route;
    }
}
