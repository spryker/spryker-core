<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Twig\Communication\RouteResolver;

interface RouteResolverInterface
{
    /**
     * @param string $controllerServiceName
     *
     * @return string
     */
    public function buildRouteFromControllerServiceName(string $controllerServiceName): string;
}
