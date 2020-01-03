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
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidator;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriter;
use Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriterInterface;
use Spryker\Zed\ResourceShare\ResourceShareDependencyProvider;

/**
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareRepositoryInterface getRepository()
 * @method \Spryker\Zed\ResourceShare\Persistence\ResourceShareEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ResourceShare\ResourceShareConfig getConfig()
 */
class ResourceShareBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareReaderInterface
     */
    public function createResourceShareReader(): ResourceShareReaderInterface
    {
        return new ResourceShareReader(
            $this->getRepository(),
            $this->createResourceShareValidator()
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
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareWriterInterface
     */
    public function createResourceShareWriter(): ResourceShareWriterInterface
    {
        return new ResourceShareWriter(
            $this->getEntityManager(),
            $this->getRepository(),
            $this->createResourceShareValidator()
        );
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShare\ResourceShareValidatorInterface
     */
    public function createResourceShareValidator(): ResourceShareValidatorInterface
    {
        return new ResourceShareValidator();
    }

    /**
     * @return \Spryker\Zed\ResourceShareExtension\Dependency\Plugin\ResourceShareZedActivatorStrategyPluginInterface[]
     */
    public function getResourceShareActivatorStrategyPlugins(): array
    {
        return $this->getProvidedDependency(ResourceShareDependencyProvider::PLUGINS_RESOURCE_SHARE_ACTIVATOR_STRATEGY);
    }
}
