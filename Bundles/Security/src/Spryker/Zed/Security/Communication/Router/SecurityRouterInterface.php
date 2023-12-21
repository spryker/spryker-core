<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Router;

use Spryker\Service\Container\ContainerInterface;

interface SecurityRouterInterface
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    public function addRouter(ContainerInterface $container): void;

    /**
     * @param string $routeNameOrUrl
     * @param string|null $routeName
     *
     * @return void
     */
    public function addSecurityRoute(
        string $routeNameOrUrl,
        ?string $routeName = null
    ): void;
}
