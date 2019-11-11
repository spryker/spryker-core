<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\FilterTransfer;
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
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\SpyMerchantOpeningHoursStorageEntityTransfer[]
     */
    public function getFilteredMerchantOpeningHoursStorageEntityTransfers(FilterTransfer $filterTransfer, array $merchantIds): array
    {
        $merchantOpeningHoursStoragePropelQuery = $this->getFactory()
            ->getMerchantOpeningHoursStoragePropelQuery();

        if ($merchantIds) {
            $merchantOpeningHoursStoragePropelQuery->filterByFkMerchant_In($merchantIds);
        }

        return $this->buildQueryFromCriteria($merchantOpeningHoursStoragePropelQuery, $filterTransfer)
            ->find();
    }

    /**
     * @module Merchant
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return \Generated\Shared\Transfer\SpyMerchantEntityTransfer[]
     */
    public function getFilteredMerchantTransfers(FilterTransfer $filterTransfer): array
    {
        $merchantOpeningHoursDateSchedulePropelQuery = $this->getFactory()
            ->getMerchantPropelQuery();

        $merchantEntityTransfers = $this->buildQueryFromCriteria($merchantOpeningHoursDateSchedulePropelQuery, $filterTransfer)
            ->find();

        return $merchantEntityTransfers;
    }
}
