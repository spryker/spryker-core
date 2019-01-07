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
class SslRouteManipulatorPlugin extends AbstractPlugin implements RouteManipulatorPluginInterface
{
    /**
     * @var array|null
     */
    protected $sslExcludedRouteNames;

    /**
     * @var bool|null
     */
    protected $isSslEnabled;

    /**
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function manipulate(string $routeName, Route $route): Route
    {
        if ($this->isSslEnabled()) {
            $route->requireHttps();
        }

        if (in_array($routeName, $this->getSslExcludedRouteNames())) {
            $route->requireHttp();
        }

        return $route;
    }

    /**
     * @return bool
     */
    protected function isSslEnabled(): bool
    {
        if ($this->isSslEnabled === null) {
            $this->isSslEnabled = $this->getConfig()->isSslEnabled();
        }

        return $this->isSslEnabled;
    }

    /**
     * @return string[]
     */
    protected function getSslExcludedRouteNames(): array
    {
        if ($this->sslExcludedRouteNames === null) {
            $this->sslExcludedRouteNames = $this->getConfig()->getSslExcludedRouteNames();
        }

        return $this->sslExcludedRouteNames;
    }
}
