<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AvailabilityStorage;

use Spryker\Client\AvailabilityStorage\Expander\ProductViewAvailabilityExpander;
use Spryker\Client\AvailabilityStorage\Expander\ProductViewAvailabilityExpanderInterface;
use Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapper;
use Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReader;
use Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AvailabilityStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AvailabilityStorage\Storage\AvailabilityStorageReaderInterface
     */
    public function createAvailabilityStorageReader(): AvailabilityStorageReaderInterface
    {
        return new AvailabilityStorageReader(
            $this->getStorage(),
            $this->getSynchronizationService(),
            $this->createAvailabilityStorageMapper()
        );
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Mapper\AvailabilityStorageMapperInterface
     */
    public function createAvailabilityStorageMapper(): AvailabilityStorageMapperInterface
    {
        return new AvailabilityStorageMapper();
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Expander\ProductViewAvailabilityExpanderInterface
     */
    public function createProductViewAvailabilityExpander(): ProductViewAvailabilityExpanderInterface
    {
        return new ProductViewAvailabilityExpander(
            $this->createAvailabilityStorageReader(),
            $this->getAvailabilityStorageStrategyPlugins()
        );
    }

    /**
     * @return \Spryker\Client\AvailabilityStorageExtension\Dependency\Plugin\AvailabilityStorageStrategyPluginInterface[]
     */
    public function getAvailabilityStorageStrategyPlugins(): array
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::PLUGINS_AVAILABILITY_STORAGE_STRATEGY);
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Dependency\Client\AvailabilityStorageToStorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\AvailabilityStorage\Dependency\Service\AvailabilityStorageToSynchronizationServiceBridge
     */
    protected function getSynchronizationService()
    {
        return $this->getProvidedDependency(AvailabilityStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
