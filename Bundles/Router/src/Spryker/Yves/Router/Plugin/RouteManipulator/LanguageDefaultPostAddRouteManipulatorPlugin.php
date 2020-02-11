<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouteManipulator;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\RouterExtension\Dependency\Plugin\PostAddRouteManipulatorPluginInterface;
use Symfony\Component\Routing\Route;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class LanguageDefaultPostAddRouteManipulatorPlugin extends AbstractPlugin implements PostAddRouteManipulatorPluginInterface
{
    /**
     * @var string
     */
    protected $allowedLocalesPattern;

    /**
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function manipulate(string $routeName, Route $route): Route
    {
        $route->setDefault('language', 'en');

        return $route;
    }
}
