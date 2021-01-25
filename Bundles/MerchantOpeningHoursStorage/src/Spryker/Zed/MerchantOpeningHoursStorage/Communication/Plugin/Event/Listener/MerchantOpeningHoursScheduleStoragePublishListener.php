<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOpeningHoursStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Business\MerchantOpeningHoursStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\Communication\MerchantOpeningHoursStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantOpeningHoursStorage\MerchantOpeningHoursStorageConfig getConfig()
 */
class MerchantOpeningHoursScheduleStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $merchantIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventEntityTransfers);

        $this->getFacade()->publish($merchantIds);
    }
}
