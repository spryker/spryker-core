<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ResourceShare\Activator\ResourceShareActivator;
use Spryker\Client\ResourceShare\Activator\ResourceShareActivatorInterface;
use Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface;
use Spryker\Client\ResourceShare\Zed\ResourceShareStub;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface
     */
    public function createZedResourceShareStub(): ResourceShareStubInterface
    {
        return new ResourceShareStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ResourceShare\Activator\ResourceShareActivatorInterface
     */
    public function createResourceShareActivator(): ResourceShareActivatorInterface
    {
        return new ResourceShareActivator(
            $this->createZedResourceShareStub(),
            $this->getBeforeZedResourceShareActivatorStrategyPlugins(),
            $this->getAfterZedResourceShareActivatorStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface
     */
    public function getZedRequestClient(): ResourceShareToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[]
     */
    public function getAfterZedResourceShareActivatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::PLUGINS_AFTER_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY);
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareClientActivatorStrategyPluginInterface[]
     */
    public function getBeforeZedResourceShareActivatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::PLUGINS_BEFORE_ZED_RESOURCE_SHARE_ACTIVATOR_STRATEGY);
    }
}
