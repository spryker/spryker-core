<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShipmentTypeStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface;
use Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToSynchronizationServiceInterface;
use Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToUtilEncodingServiceInterface;
use Spryker\Client\ShipmentTypeStorage\Generator\ShipmentTypeStorageKeyGenerator;
use Spryker\Client\ShipmentTypeStorage\Generator\ShipmentTypeStorageKeyGeneratorInterface;
use Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReader;
use Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReaderInterface;

class ShipmentTypeStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReaderInterface
     */
    public function createShipmentTypeStorageReader(): ShipmentTypeStorageReaderInterface
    {
        return new ShipmentTypeStorageReader(
            $this->createShipmentTypeStorageKeyGenerator(),
            $this->getStorageClient(),
            $this->getUtilEncodingService(),
        );
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Generator\ShipmentTypeStorageKeyGeneratorInterface
     */
    public function createShipmentTypeStorageKeyGenerator(): ShipmentTypeStorageKeyGeneratorInterface
    {
        return new ShipmentTypeStorageKeyGenerator($this->getSynchronizationService());
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Dependency\Client\ShipmentTypeStorageToStorageClientInterface
     */
    public function getStorageClient(): ShipmentTypeStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): ShipmentTypeStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Dependency\Service\ShipmentTypeStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ShipmentTypeStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
