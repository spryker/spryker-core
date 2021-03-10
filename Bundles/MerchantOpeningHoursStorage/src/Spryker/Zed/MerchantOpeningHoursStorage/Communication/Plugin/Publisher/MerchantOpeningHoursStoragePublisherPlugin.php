<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Publisher;

use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursDateScheduleTableMap;
use Orm\Zed\MerchantOpeningHours\Persistence\Map\SpyMerchantOpeningHoursWeekdayScheduleTableMap;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantOpeningHours\Dependency\MerchantOpeningHoursEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Business\MerchantOpeningHoursStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Communication\MerchantOpeningHoursStorageCommunicationFactory getFactory()
 */
class MerchantOpeningHoursStoragePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * @inheritDoc
     *
     * @api
     *
     * @param array $eventEntityTransfers
     * @param string $eventName
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        switch ($eventName) {
            case MerchantOpeningHoursEvents::MERCHANT_OPENING_HOURS_PUBLISH:
                $merchantIds = $this->getFactory()
                    ->getEventBehaviorFacade()
                    ->getEventTransferIds($eventEntityTransfers);

                break;
            case MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE_CREATE:
                $merchantIds = $this->getFactory()
                    ->getEventBehaviorFacade()
                    ->getEventTransferForeignKeys(
                        $eventEntityTransfers,
                        SpyMerchantOpeningHoursWeekdayScheduleTableMap::COL_FK_MERCHANT
                    );

                break;
            case MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE_CREATE:
                $merchantIds = $this->getFactory()
                    ->getEventBehaviorFacade()
                    ->getEventTransferForeignKeys(
                        $eventEntityTransfers,
                        SpyMerchantOpeningHoursDateScheduleTableMap::COL_FK_MERCHANT
                    );

                break;
            default:
                $merchantIds = [];

                break;
        }

        if (empty($merchantIds) === false) {
            $this->getFacade()->publish($merchantIds);
        }
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            MerchantOpeningHoursEvents::MERCHANT_OPENING_HOURS_PUBLISH,
            MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE_CREATE,
            MerchantOpeningHoursEvents::ENTITY_SPY_MERCHANT_OPENING_HOURS_DATE_SCHEDULE_CREATE,
        ];
    }
}
