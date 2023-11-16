<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EventDispatcher\Extender;

use Spryker\Service\Container\ContainerInterface;

interface EventDispatcherExtenderInterface
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function extend(ContainerInterface $container): ContainerInterface;
}
