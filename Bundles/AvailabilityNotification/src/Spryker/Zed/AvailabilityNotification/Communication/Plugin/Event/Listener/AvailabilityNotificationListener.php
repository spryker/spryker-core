<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\AvailabilityNotificationDataTransfer;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacadeInterface getFacade()
 * @method \Spryker\Zed\AvailabilityNotification\Communication\AvailabilityNotificationCommunicationFactory getFactory()
 * @method \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig getConfig()
 */
class AvailabilityNotificationListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * - Notify subscribed users when product is available again.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityNotificationDataTransfer[] $transfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $transfers, $eventName)
    {
        foreach ($transfers as $availabilityNotificationDataTransfer) {
            if (!$availabilityNotificationDataTransfer instanceof AvailabilityNotificationDataTransfer) {
                continue;
            }

            $this->getFacade()
                ->sendAvailabilityNotificationSubscriptionNotification($availabilityNotificationDataTransfer);
        }
    }
}
