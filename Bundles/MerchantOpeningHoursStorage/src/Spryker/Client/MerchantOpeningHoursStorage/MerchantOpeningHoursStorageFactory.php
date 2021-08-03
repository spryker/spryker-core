<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantOpeningHoursStorage;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\MerchantOpeningHoursStorage\Dependency\Client\MerchantOpeningHoursStorageToStorageClientInterface;
use Spryker\Client\MerchantOpeningHoursStorage\Dependency\Service\MerchantOpeningHoursStorageToSynchronizationServiceInterface;
use Spryker\Client\MerchantOpeningHoursStorage\Dependency\Service\MerchantOpeningHoursStorageToUtilEncodingServiceInterface;
use Spryker\Client\MerchantOpeningHoursStorage\Mapper\MerchantOpeningHoursMapper;
use Spryker\Client\MerchantOpeningHoursStorage\Mapper\MerchantOpeningHoursMapperInterface;
use Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter\DateScheduleFilter;
use Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter\DateScheduleFilterInterface;
use Spryker\Client\MerchantOpeningHoursStorage\Reader\MerchantOpeningHoursStorageReader;
use Spryker\Client\MerchantOpeningHoursStorage\Reader\MerchantOpeningHoursStorageReaderInterface;

class MerchantOpeningHoursStorageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\MerchantOpeningHoursStorage\Reader\MerchantOpeningHoursStorageReaderInterface
     */
    public function createMerchantOpeningHoursStorageReader(): MerchantOpeningHoursStorageReaderInterface
    {
        return new MerchantOpeningHoursStorageReader(
            $this->getStorageClient(),
            $this->getSynchronizationService(),
            $this->createMerchantOpeningHoursMapper(),
            $this->getUtilEncodingService(),
            $this->createDateScheduleFilter()
        );
    }

    /**
     * @return \Spryker\Client\MerchantOpeningHoursStorage\Reader\Filter\DateScheduleFilterInterface
     */
    public function createDateScheduleFilter(): DateScheduleFilterInterface
    {
        return new DateScheduleFilter();
    }

    /**
     * @return \Spryker\Client\MerchantOpeningHoursStorage\Mapper\MerchantOpeningHoursMapperInterface
     */
    public function createMerchantOpeningHoursMapper(): MerchantOpeningHoursMapperInterface
    {
        return new MerchantOpeningHoursMapper();
    }

    /**
     * @return \Spryker\Client\MerchantOpeningHoursStorage\Dependency\Client\MerchantOpeningHoursStorageToStorageClientInterface
     */
    public function getStorageClient(): MerchantOpeningHoursStorageToStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantOpeningHoursStorageDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\MerchantOpeningHoursStorage\Dependency\Service\MerchantOpeningHoursStorageToSynchronizationServiceInterface
     */
    public function getSynchronizationService(): MerchantOpeningHoursStorageToSynchronizationServiceInterface
    {
        return $this->getProvidedDependency(MerchantOpeningHoursStorageDependencyProvider::SERVICE_SYNCHRONIZATION);
    }

    /**
     * @return \Spryker\Client\MerchantOpeningHoursStorage\Dependency\Service\MerchantOpeningHoursStorageToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): MerchantOpeningHoursStorageToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MerchantOpeningHoursStorageDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
