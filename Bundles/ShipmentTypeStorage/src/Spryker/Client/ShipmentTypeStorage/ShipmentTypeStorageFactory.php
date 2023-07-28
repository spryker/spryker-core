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
use Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeReader;
use Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeReaderInterface;
use Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReader;
use Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeStorageReaderInterface;
use Spryker\Client\ShipmentTypeStorage\Scanner\ShipmentTypeStorageKeyScanner;
use Spryker\Client\ShipmentTypeStorage\Scanner\ShipmentTypeStorageKeyScannerInterface;

/**
 * @method \Spryker\Client\ShipmentTypeStorage\ShipmentTypeStorageConfig getConfig()
 */
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
            $this->createShipmentTypeStorageKeyScanner(),
        );
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Scanner\ShipmentTypeStorageKeyScannerInterface
     */
    public function createShipmentTypeStorageKeyScanner(): ShipmentTypeStorageKeyScannerInterface
    {
        return new ShipmentTypeStorageKeyScanner(
            $this->getStorageClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Client\ShipmentTypeStorage\Reader\ShipmentTypeReaderInterface
     */
    public function createShipmentTypeReader(): ShipmentTypeReaderInterface
    {
        return new ShipmentTypeReader(
            $this->createShipmentTypeStorageReader(),
            $this->getAvailableShipmentTypeFilterPlugins(),
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

    /**
     * @return array<\Spryker\Client\ShipmentTypeStorageExtension\Dependency\Plugin\AvailableShipmentTypeFilterPluginInterface>
     */
    public function getAvailableShipmentTypeFilterPlugins(): array
    {
        return $this->getProvidedDependency(ShipmentTypeStorageDependencyProvider::PLUGINS_AVAILABLE_SHIPMENT_TYPE_FILTER);
    }
}
