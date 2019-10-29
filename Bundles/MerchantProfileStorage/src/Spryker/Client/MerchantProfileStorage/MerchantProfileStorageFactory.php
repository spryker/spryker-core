<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface;
use Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface;
use Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapper;
use Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapperInterface;
use Spryker\Client\MerchantProfileStorage\Storage\MerchantProfileStorageReader;
use Spryker\Client\MerchantProfileStorage\Storage\MerchantProfileStorageReaderInterface;

class MerchantProfileStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantProfileStorage\Storage\MerchantProfileStorageReaderInterface
     */
    public function createMerchantProfileStorageReader(): MerchantProfileStorageReaderInterface
    {
        return new MerchantProfileStorageReader(
            $this->createMerchantProfileStorageMapper(),
            $this->getSynchronizationService(),
            $this->getStorageClient()
        );
    }

    /**
     * @return \Spryker\Client\MerchantProfileStorage\Mapper\MerchantProfileStorageMapperInterface
     */
    public function createMerchantProfileStorageMapper(): MerchantProfileStorageMapperInterface
    {
        return new MerchantProfileStorageMapper();
    }

    /**
     * @return \Spryker\Client\MerchantProfileStorage\Dependency\Service\MerchantProfileStorageConnectorToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): MerchantProfileStorageConnectorToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(MerchantProfileStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\MerchantProfileStorage\Dependency\Client\MerchantProfileStorageToStorageClientInterface
     */
    public function getStorageClient(): MerchantProfileStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProfileStorageDependencyProvider::CLIENT_STORAGE);
    }
}
