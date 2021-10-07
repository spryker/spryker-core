<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Business\MerchantOpeningHoursStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Persistence\MerchantOpeningHoursStorageRepositoryInterface getRepository()
 */
class MerchantOpeningHoursStorageFacade extends AbstractFacade implements MerchantOpeningHoursStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantOpeningHoursWeekdayScheduleEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createMerchantOpeningHoursStoragePublisher()
            ->writeCollectionByMerchantOpeningHoursWeekdayScheduleEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantOpeningHoursDateScheduleEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createMerchantOpeningHoursStoragePublisher()
            ->writeCollectionByMerchantOpeningHoursDateScheduleEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByMerchantOpeningHoursEvents(array $eventEntityTransfers): void
    {
        $this->getFactory()
            ->createMerchantOpeningHoursStoragePublisher()
            ->writeCollectionByMerchantOpeningHoursEvents($eventEntityTransfers);
    }
}
