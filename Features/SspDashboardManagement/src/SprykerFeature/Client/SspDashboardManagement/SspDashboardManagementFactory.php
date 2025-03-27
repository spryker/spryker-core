<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspDashboardManagement;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use SprykerFeature\Client\SspDashboardManagement\Storage\CmsBlockCompanyBusinessUnitStorageReader;
use SprykerFeature\Client\SspDashboardManagement\Storage\CmsBlockCompanyBusinessUnitStorageReaderInterface;
use SprykerFeature\Client\SspDashboardManagement\Zed\SspDashboardManagementStub;
use SprykerFeature\Client\SspDashboardManagement\Zed\SspDashboardManagementStubInterface;

class SspDashboardManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Client\SspDashboardManagement\Zed\SspDashboardManagementStubInterface
     */
    public function createSspDashboardManagementStub(): SspDashboardManagementStubInterface
    {
        return new SspDashboardManagementStub($this->getZedRequestClient());
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \SprykerFeature\Client\SspDashboardManagement\Storage\CmsBlockCompanyBusinessUnitStorageReaderInterface
     */
    public function createCmsBlockCompanyBusinessUnitStorageReader(): CmsBlockCompanyBusinessUnitStorageReaderInterface
    {
        return new CmsBlockCompanyBusinessUnitStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->getStoreClient(),
            $this->getLocaleClient(),
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    public function getStorageClient(): StorageClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Service\Synchronization\SynchronizationServiceInterface
     */
    public function getSynchronizationService(): SynchronizationServiceInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Client\Locale\LocaleClientInterface
     */
    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_LOCALE);
    }
}
