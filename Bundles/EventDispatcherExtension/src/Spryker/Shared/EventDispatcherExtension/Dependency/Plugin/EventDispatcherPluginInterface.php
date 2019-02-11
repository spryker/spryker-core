<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventDispatcherExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface EventDispatcherPluginInterface
{
    /**
     * Specification:
     * - Returns Event Subscriber that will be added for Event Dispatcher.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function getSubscriber(ContainerInterface $container): EventSubscriberInterface;
}
