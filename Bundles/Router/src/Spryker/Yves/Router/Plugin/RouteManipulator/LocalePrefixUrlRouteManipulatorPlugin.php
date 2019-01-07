<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouteManipulator;

use Spryker\Shared\RouterExtension\Dependency\Plugin\RouteManipulatorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Routing\Route;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class LocalePrefixUrlRouteManipulatorPlugin extends AbstractPlugin implements RouteManipulatorPluginInterface
{
    /**
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function manipulate(string $routeName, Route $route): Route
    {
        $path = $route->getPath();
        $path = sprintf('/{_locale}%s', ltrim($path, '/'));

        $route->setPath($path);
        $route->assert('_locale', 'en|de');
        $route->value('_locale', '');

        return $route;
    }
}
