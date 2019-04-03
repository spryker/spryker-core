<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ResourceShare\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareActivator;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareActivatorInterface;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReader;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriter;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriterInterface;
use Spryker\Zed\ResourceShare\Business\Uuid\ResourceShareUuidGenerator;
use Spryker\Zed\ResourceShare\Business\Uuid\ResourceShareUuidGeneratorInterface;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface;
use Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilUuidGeneratorServiceInterface;
use Spryker\Zed\ResourceShare\ResourceShareDependencyProvider;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface getRepository()
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface getEntityManager()
 */
class ResourceShareBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    public function createResourceShareReader(): ResourceShareReaderInterface
    {
        return new ResourceShareReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriterInterface
     */
    public function createResourceShareWriter(): ResourceShareWriterInterface
    {
        return new ResourceShareWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createResourceShareUuidGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\Uuid\ResourceShareUuidGeneratorInterface
     */
    public function createResourceShareUuidGenerator(): ResourceShareUuidGeneratorInterface
    {
        return new ResourceShareUuidGenerator(
            $this->getUtilEncodingService(),
            $this->getUtilUuidService()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareActivatorInterface
     */
    public function createResourceShareActivator(): ResourceShareActivatorInterface
    {
        return new ResourceShareActivator(
            $this->createResourceShareReader(),
            $this->getResourceShareActivatorStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ResourceShareToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Dependency\Service\ResourceShareToUtilUuidGeneratorServiceInterface
     */
    public function getUtilUuidService(): ResourceShareToUtilUuidGeneratorServiceInterface
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::SERVICE_UTIL_UUID_GENERATOR);
    }

    /**
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareActivatorStrategyPluginInterface[]
     */
    public function getResourceShareActivatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY);
    }
}
