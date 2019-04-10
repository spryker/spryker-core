<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouteManipulator;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Router\Route\Route;
use Spryker\Shared\RouterExtension\Dependency\Plugin\RouteManipulatorPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Yves\Router\RouterConfig getConfig()
 */
class LocalePrefixUrlRouteManipulatorPlugin extends AbstractPlugin implements RouteManipulatorPluginInterface
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
        if ($routeName === '/') {
            $route->setPath('/{locale}');
            $route->assert('locale', $this->getAllowedLocalesPattern());
            $route->value('locale', '');

            return $route;
        }

        $path = ltrim($route->getPath(), '/');
        $pathFragments = explode('/', $path);
        $firstElement = array_shift($pathFragments);

        $path = sprintf('/{locale}/%s', implode($pathFragments));

        $route->setPath($path);
        $route->assert('locale', sprintf('%s%2$s|%2$s', $this->getAllowedLocalesPattern(), $firstElement));
        $route->value('locale', $firstElement);

        return $route;
    }

    /**
     * @return string
     */
    public function getAllowedLocalesPattern()
    {
        if ($this->allowedLocalesPattern !== null) {
            return $this->allowedLocalesPattern;
        }

        $systemLocales = Store::getInstance()->getLocales();
        $implodedLocales = implode('|', array_keys($systemLocales));
        $this->allowedLocalesPattern = '(' . $implodedLocales . ')\/';

        return $this->allowedLocalesPattern;
    }
}
