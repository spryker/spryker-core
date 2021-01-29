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
     * @param string $name
     * @param \Symfony\Component\Routing\Route|\Spryker\Yves\Router\Route\Route $route
     * @param int $priority
     *
     * @return void
     */
    public function add($name, Route $route, int $priority = 0): void
    {
        foreach ($this->routeManipulator as $routeManipulator) {
            $route = $routeManipulator->manipulate($name, $route);
        }

        parent::add($name, $route, $priority);
    }
}
