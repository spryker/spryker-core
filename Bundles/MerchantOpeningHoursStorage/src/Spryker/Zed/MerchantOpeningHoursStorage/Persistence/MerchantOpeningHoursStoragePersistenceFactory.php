<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery;
use Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery;
use Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;
use Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageDependencyProvider;
use Spryker\Zed\MerchantOpeningHoursStorage\Persistence\Propel\Mapper\MerchantOpeningHoursMapper;
use Spryker\Zed\MerchantOpeningHoursStorage\Persistence\Propel\Mapper\MerchantOpeningHoursMapperInterface;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface getRepository()
 */
class MerchantOpeningHoursStoragePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery
     */
    public function getMerchantOpeningHoursStoragePropelQuery(): SpyMerchantOpeningHoursStorageQuery
    {
        return SpyMerchantOpeningHoursStorageQuery::create();
    }

    /**
     * @return \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\Propel\Mapper\MerchantOpeningHoursMapperInterface
     */
    public function createMerchantOpeningHoursMapper(): MerchantOpeningHoursMapperInterface
    {
        return new MerchantOpeningHoursMapper();
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursWeekdayScheduleQuery
     */
    public function getMerchantOpeningHoursWeekdaySchedulePropelQuery(): SpyMerchantOpeningHoursWeekdayScheduleQuery
    {
        return $this->getProvidedDependency(MerchantOpeningHoursStorageDependencyProvider::PROPEL_QUERY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE);
    }

    /**
     * @return \Orm\Zed\MerchantOpeningHours\Persistence\SpyMerchantOpeningHoursDateScheduleQuery
     */
    public function getMerchantOpeningHoursDateSchedulePropelQuery(): SpyMerchantOpeningHoursDateScheduleQuery
    {
        return $this->getProvidedDependency(MerchantOpeningHoursStorageDependencyProvider::PROPEL_QUERY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE);
    }
}
