<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductDiscontinued\Dependency\ProductDiscontinuedEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Business\ProductDiscontinuedStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductDiscontinuedStorage\Communication\ProductDiscontinuedStorageCommunicationFactory getFactory()
 */
class ProductDiscontinuedStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

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

        $productDiscontinuedIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        if (empty($productDiscontinuedIds)) {
            return;
        }

        if ($eventName === ProductDiscontinuedEvents::ENTITY_SPY_PRODUCT_DISCONTINUED_DELETE
            || $eventName === ProductDiscontinuedEvents::PRODUCT_DISCONTINUED_UNPUBLISH
        ) {
            $this->getFacade()->unpublish($productDiscontinuedIds);

            return;
        }

        $this->getFacade()->publish($productDiscontinuedIds);
    }
}
