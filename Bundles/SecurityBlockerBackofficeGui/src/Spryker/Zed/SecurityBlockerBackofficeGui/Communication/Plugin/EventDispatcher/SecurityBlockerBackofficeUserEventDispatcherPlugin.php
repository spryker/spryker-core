<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SecurityBlockerBackofficeGui\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SecurityBlockerBackofficeGui\Communication\SecurityBlockerBackofficeGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\SecurityBlockerBackofficeGui\SecurityBlockerBackofficeGuiConfig getConfig()
 */
class SecurityBlockerBackofficeUserEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds a listener to log the failed Backoffice login attempts.
     * - Denies user access in case of exceeding the limit.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(
        EventDispatcherInterface $eventDispatcher,
        ContainerInterface $container
    ): EventDispatcherInterface {
        $eventDispatcher->addSubscriber(
            $this->getFactory()->createSecurityBlockerBackOfficeUserEventSubscriber(),
        );

        return $eventDispatcher;
    }
}
