<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ApplicationExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;

interface ApplicationPluginInterface
{
    /**
     * Specification:
     * - Adds an ApplicationPlugin to the application.
     * - Do not use `ContainerInterface::get()` outside of a callback.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface;
}
