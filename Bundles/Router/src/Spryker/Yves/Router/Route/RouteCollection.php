<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Route;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection as SymfonyRouteCollection;

class RouteCollection extends SymfonyRouteCollection
{
    /**
     * @var \Spryker\Yves\RouterExtension\Dependency\Plugin\PostAddRouteManipulatorPluginInterface[]
     */
    protected $routeManipulator;

    /**
     * @param array $routeManipulator
     */
    public function __construct(array $routeManipulator = [])
    {
        $this->routeManipulator = $routeManipulator;
    }

    /**
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route|\Spryker\Yves\Router\Route\Route $route
     *
     * @return void
     */
    public function add($routeName, Route $route): void
    {
        foreach ($this->routeManipulator as $routeManipulator) {
            $route = $routeManipulator->manipulate($routeName, $route);
        }

        parent::add($routeName, $route);
    }
}
