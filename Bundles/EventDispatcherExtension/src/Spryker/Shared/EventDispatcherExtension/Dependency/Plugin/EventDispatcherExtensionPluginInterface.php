<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\EventDispatcherExtension\Dependency\Plugin;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

interface EventDispatcherExtensionPluginInterface
{
    /**
     * Specification:
     * - Returns Event Subscriber, that will be added for Event Dispatcher.
     *
     * @api
     *
     * @return \Symfony\Component\EventDispatcher\EventSubscriberInterface
     */
    public function getSubscriber(): EventSubscriberInterface;
}
