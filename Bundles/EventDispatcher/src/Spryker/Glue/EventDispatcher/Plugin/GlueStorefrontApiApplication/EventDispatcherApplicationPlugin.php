<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EventDispatcher\Plugin\GlueStorefrontApiApplication;

use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;

/**
 * @method \Spryker\Glue\EventDispatcher\EventDispatcherFactory getFactory()
 */
class EventDispatcherApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Extends EventDispatcher with Storefront EventDispatcherPluginInterface plugins.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        return $this->getFactory()->createStorefrontEventDispatcherExtender()->extend($container);
    }
}
