<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityNotification\Communication\AvailabilityNotificationCommunicationFactory getFactory()
 */
class AvailabilityNotificationListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * Specification
     *  - Listeners needs to implement this interface to execute the codes for more
     *  than one event at same time (Bulk Operation)
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName)
    {
        foreach ($transfers as $transfer) {
            $this->getFacade()
                ->processAvailabilityNotificationSubscription(
                    $transfer->getSku(),
                    $transfer->getStore(),
                    $transfer->getProductConcrete()
                );
        }
    }
}
