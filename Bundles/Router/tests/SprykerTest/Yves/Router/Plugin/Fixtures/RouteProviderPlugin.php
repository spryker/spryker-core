<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Yves\Router\Plugin\Fixtures;

use Spryker\Shared\Router\Route\Route;
use Spryker\Shared\Router\Route\RouteCollection;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class RouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/foo', 'Router', 'Router');
        $routeCollection->add('foo', $route);

        $route = $this->buildRoute('/', 'Router', 'Router');
        $routeCollection->add('home', $route);

        return $routeCollection;
    }

    /**
     * @param string $path
     * @param string $moduleName
     * @param string $controllerName
     * @param string $actionName
     * @param bool $parseJsonBody
     *
     * @return \Spryker\Shared\Router\Route\Route
     */
    protected function buildRoute(string $path, string $moduleName, string $controllerName, string $actionName = 'indexAction', bool $parseJsonBody = false): Route
    {
        $route = new Route($path);

        $template = sprintf(
            '%s/%s/%s',
            $moduleName,
            $controllerName,
            $actionName
        );

        $route->setDefault('_controller', [$this, $actionName]);
        $route->setDefault('_template', $template);

        return $route;
    }
}
