<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Price\Dependency\PriceEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceStorage\Communication\PriceStorageCommunicationFactory getFactory()
 */
class PriceTypeProductAbstractStorageListener extends AbstractPriceProductAbstractStorageListener implements EventBulkHandlerInterface
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
        $priceTypeIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $productAbstractIds = $this->getQueryContainer()->queryAllProductAbstractIdsByPriceTypeIds($priceTypeIds)->find()->getData();

        if ($eventName === PriceEvents::ENTITY_SPY_PRICE_TYPE_CREATE || $eventName === PriceEvents::ENTITY_SPY_PRICE_TYPE_UPDATE) {
            $this->publish($productAbstractIds);
        } elseif($eventName === PriceEvents::ENTITY_SPY_PRICE_TYPE_DELETE) {
            $this->unpublish($productAbstractIds);
        }
    }

}
