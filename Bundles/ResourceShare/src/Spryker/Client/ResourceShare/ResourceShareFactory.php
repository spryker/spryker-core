<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ResourceShare;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface;
use Spryker\Client\ResourceShare\ResourceShare\ResourceShareActivator;
use Spryker\Client\ResourceShare\ResourceShare\ResourceShareActivatorInterface;
use Spryker\Client\ResourceShare\ResourceShare\ResourceShareExpander;
use Spryker\Client\ResourceShare\ResourceShare\ResourceShareExpanderInterface;
use Spryker\Client\ResourceShare\ResourceShare\ResourceShareGenerator;
use Spryker\Client\ResourceShare\ResourceShare\ResourceShareGeneratorInterface;
use Spryker\Client\ResourceShare\Zed\ResourceShareStub;
use Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface;

class ResourceShareFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ResourceShare\ResourceShare\ResourceShareGeneratorInterface
     */
    public function createResourceShareGenerator(): ResourceShareGeneratorInterface
    {
        return new ResourceShareGenerator(
            $this->createZedResourceShareStub(),
            $this->createResourceShareExpander()
        );
    }

    /**
     * @return \Spryker\Client\ResourceShare\ResourceShare\ResourceShareActivatorInterface
     */
    public function createResourceShareActivator(): ResourceShareActivatorInterface
    {
        return new ResourceShareActivator(
            $this->createZedResourceShareStub(),
            $this->createResourceShareExpander()
        );
    }

    /**
     * @return \Spryker\Client\ResourceShare\ResourceShare\ResourceShareExpanderInterface
     */
    public function createResourceShareExpander(): ResourceShareExpanderInterface
    {
        return new ResourceShareExpander(
            $this->getResourceShareResourceDataExpanderStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Client\ResourceShare\Zed\ResourceShareStubInterface
     */
    public function createZedResourceShareStub(): ResourceShareStubInterface
    {
        return new ResourceShareStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ResourceShare\Dependency\Client\ResourceShareToZedRequestClientInterface
     */
    public function getZedRequestClient(): ResourceShareToZedRequestClientInterface
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\ResourceShareExtension\Dependency\Plugin\ResourceShareResourceDataExpanderStrategyPluginInterface[]
     */
    public function getResourceShareResourceDataExpanderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::PLUGINS_RESOURCE_SHARE_RESOURCE_DATA_EXPANDER_STRATEGY);
    }
}
