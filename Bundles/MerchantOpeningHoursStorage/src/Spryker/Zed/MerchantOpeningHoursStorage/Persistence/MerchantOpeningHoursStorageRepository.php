<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\MerchantOpeningHoursStorageCriteriaTransfer;
use Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStoragePersistenceFactory getFactory()
 */
class MerchantOpeningHoursStorageRepository extends AbstractRepository implements MerchantOpeningHoursStorageRepositoryInterface
{
    /**
     * @module MerchantOpeningHours
     *
     * @param int $fkMerchant
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\WeekdayScheduleTransfer[]
     */
    public function getMerchantOpeningHoursWeekdayScheduleByFkMerchant(int $fkMerchant): ArrayObject
    {
        $weekdayScheduleEntities = $this->getFactory()
            ->getMerchantOpeningHoursWeekdaySchedulePropelQuery()
            ->useSpyWeekdayScheduleQuery()
                ->orderByDay()
                ->orderByTimeFrom()
            ->endUse()
            ->filterByFkMerchant($fkMerchant)
            ->find();

        return $this->getFactory()
            ->createMerchantOpeningHoursMapper()
            ->mapMerchantOpeningHoursWeekdayScheduleEntitiesToWeekdayScheduleTransfers($weekdayScheduleEntities, new ArrayObject());
    }

    /**
     * @module MerchantOpeningHours
     *
     * @param int $fkMerchant
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\DateScheduleTransfer[]
     */
    public function getMerchantOpeningHoursDateScheduleByFkMerchant(int $fkMerchant): ArrayObject
    {
        $dateScheduleEntities = $this->getFactory()
            ->getMerchantOpeningHoursDateSchedulePropelQuery()
            ->useSpyDateScheduleQuery()
                ->orderByDate()
                ->orderByTimeFrom()
            ->endUse()
            ->filterByFkMerchant($fkMerchant)
            ->find();

        return $this->getFactory()
            ->createMerchantOpeningHoursMapper()
            ->mapMerchantOpeningHoursDateScheduleEntitiesToDateScheduleTransfers($dateScheduleEntities, new ArrayObject());
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageCriteriaTransfer $merchantOpeningHoursStorageCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SpyMerchantOpeningHoursStorageEntityTransfer[]
     */
    public function getFilteredMerchantOpeningHoursStorageEntityTransfers(
        MerchantOpeningHoursStorageCriteriaTransfer $merchantOpeningHoursStorageCriteriaTransfer
    ): array {
        $merchantOpeningHoursStoragePropelQuery = $this->applyFilters(
            $this->getFactory()->getMerchantOpeningHoursStoragePropelQuery(),
            $merchantOpeningHoursStorageCriteriaTransfer
        );

        return $this->buildQueryFromCriteria($merchantOpeningHoursStoragePropelQuery, $merchantOpeningHoursStorageCriteriaTransfer->getFilter())
            ->find();
    }

    /**
     * @param \Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery $merchantOpeningHoursStoragePropelQuery
     * @param \Generated\Shared\Transfer\MerchantOpeningHoursStorageCriteriaTransfer $merchantOpeningHoursStorageCriteriaTransfer
     *
     * @return \Orm\Zed\MerchantOpeningHoursStorage\Persistence\SpyMerchantOpeningHoursStorageQuery
     */
    protected function applyFilters(
        SpyMerchantOpeningHoursStorageQuery $merchantOpeningHoursStoragePropelQuery,
        MerchantOpeningHoursStorageCriteriaTransfer $merchantOpeningHoursStorageCriteriaTransfer
    ): SpyMerchantOpeningHoursStorageQuery {
        if ($merchantOpeningHoursStorageCriteriaTransfer->getMerchantIds()) {
            $merchantOpeningHoursStoragePropelQuery->filterByFkMerchant_In($merchantOpeningHoursStorageCriteriaTransfer->getMerchantIds());
        }

        return $merchantOpeningHoursStoragePropelQuery;
    }
}
