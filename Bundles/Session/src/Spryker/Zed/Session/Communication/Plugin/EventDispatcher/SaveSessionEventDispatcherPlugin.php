<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Session\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Session\SessionConfig getConfig()
 * @method \Spryker\Zed\Session\Communication\SessionCommunicationFactory getFactory()
 * @method \Spryker\Zed\Session\Business\SessionFacadeInterface getFacade()
 */
class SaveSessionEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `SaveSessionListener` to event dispatcher.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addSubscriber($this->getFactory()->createSaveSessionEventSubscriber());

        return $eventDispatcher;
    }
}
