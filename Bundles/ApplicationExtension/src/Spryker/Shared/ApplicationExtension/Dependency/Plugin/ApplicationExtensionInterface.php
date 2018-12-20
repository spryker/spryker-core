<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ApplicationExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;

interface ApplicationExtensionInterface
{
    /**
     * Specification:
     * - Adds an ApplicationExtension to the application.
     * - Do not use ContainerInterface::get() outside of a callback.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return void
     */
    public function provideExtension(ContainerInterface $container): void;
}
