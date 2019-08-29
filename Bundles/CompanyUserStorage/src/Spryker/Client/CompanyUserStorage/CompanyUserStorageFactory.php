<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage;

use Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface;
use Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface;
use Spryker\Client\CompanyUserStorage\Storage\CompanyUserStorage;
use Spryker\Client\CompanyUserStorage\Storage\CompanyUserStorageInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Spryker\Client\CompanyUserStorage\CompanyUserStorageConfig getConfig()
 */
class CompanyUserStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyUserStorage\Storage\CompanyUserStorageInterface
     */
    public function createCompanyUserStorage(): CompanyUserStorageInterface
    {
        return new CompanyUserStorage(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Client\CompanyUserStorage\Dependency\Client\CompanyUserStorageToStorageClientInterface
     */
    public function getStorageClient(): CompanyUserStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyUserStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\CompanyUserStorage\Dependency\Service\CompanyUserStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): CompanyUserStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(CompanyUserStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }
}
