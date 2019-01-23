<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\EventDispatcher;

use Spryker\Yves\Kernel\AbstractFactory;

class EventDispatcherFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherExtensionPluginInterface[]
     */
    public function getEventDispatcherExtensionPlugins(): array
    {
        return $this->getProvidedDependency(EventDispatcherDependencyProvider::PLUGINS_EVENT_DISPATCHER_EXTENSIONS);
    }
}
