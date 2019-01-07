<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Router\RouterDependencyProvider;

/**
 * @method \Spryker\Zed\Router\RouterConfig getConfig()
 */
class RouterCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Shared\RouterExtension\Dependency\Plugin\RouterPluginInterface[]
     */
    public function getRouterPlugins(): array
    {
        return $this->getProvidedDependency(RouterDependencyProvider::ROUTER_PLUGINS);
    }
}
