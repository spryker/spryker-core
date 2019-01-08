<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ApplicationExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;

interface BootableApplicationPluginInterface
{
    /**
     * Specification:
     * - Boots an ApplicationPlugin.
     * - This method is called after all services are registered and should be used for "dynamic" configuration.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function boot(ContainerInterface $container): ContainerInterface;
}
