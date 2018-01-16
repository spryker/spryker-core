<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\AvailabilityStorage\Persistence\AvailabilityStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\AvailabilityStorage\Communication\AvailabilityStorageCommunicationFactory getFactory()
 */
class AvailabilityStorageListener extends AbstractAvailabilityStorageListener implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * AvailabilityStorageListener constructor.
     *
     * @param bool $isSendingToQueue
     */
    public function __construct($isSendingToQueue = true)
    {
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $availabilityIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        if ($eventName === AvailabilityEvents::ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE ||
            $eventName === AvailabilityEvents::AVAILABILITY_ABSTRACT_UNPUBLISH
        ) {
            $this->unpublish($availabilityIds, $this->isSendingToQueue);

            return;
        }

        $this->publish($availabilityIds, $this->isSendingToQueue);
    }
}
