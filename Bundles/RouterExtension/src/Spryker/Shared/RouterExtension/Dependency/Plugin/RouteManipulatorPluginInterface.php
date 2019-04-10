<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\RouterExtension\Dependency\Plugin;

use Spryker\Shared\Router\Route\Route;

interface RouteManipulatorPluginInterface
{
    /**
     * Specification:
     * - Returns a manipulated Route.
     *
     * @api
     *
     * @param string $routeName
     * @param \Spryker\Shared\Router\Route\Route $route
     *
     * @return \Spryker\Shared\Router\Route\Route
     */
    public function manipulate(string $routeName, Route $route): Route;
}
