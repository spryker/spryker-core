<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\RouterExtension\Dependency\Plugin;

use Symfony\Component\Routing\Route;

interface PostAddRouteManipulatorPluginInterface
{
    /**
     * Specification:
     * - Returns a manipulated Route.
     *
     * @api
     *
     * @param string $routeName
     * @param \Symfony\Component\Routing\Route $route
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function manipulate(string $routeName, Route $route): Route;
}
