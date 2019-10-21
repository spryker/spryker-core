<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EventDispatcher;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Shared\EventDispatcher\EventDispatcher;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;

class EventDispatcherFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    public function getEventDispatcherPlugins(): array
    {
        return $this->getProvidedDependency(EventDispatcherDependencyProvider::PLUGINS_EVENT_DISPATCHER);
    }

    /**
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function createEventDispatcher(): EventDispatcherInterface
    {
        return new EventDispatcher();
    }
}
