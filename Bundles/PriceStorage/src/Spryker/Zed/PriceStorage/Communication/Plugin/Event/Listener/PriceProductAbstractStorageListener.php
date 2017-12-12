<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Price\Persistence\Map\SpyPriceProductTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Price\Dependency\PriceEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\PriceStorage\Persistence\PriceStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceStorage\Communication\PriceStorageCommunicationFactory getFactory()
 */
class PriceProductAbstractStorageListener extends AbstractPriceProductAbstractStorageListener implements EventBulkHandlerInterface
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
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyPriceProductTableMap::COL_FK_PRODUCT_ABSTRACT);

        if ($eventName === PriceEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE || $eventName === PriceEvents::ENTITY_SPY_PRICE_PRODUCT_UPDATE) {
            $this->publish($productAbstractIds);
        } elseif($eventName === PriceEvents::ENTITY_SPY_PRICE_PRODUCT_DELETE) {
            $this->refresh($productAbstractIds);
        }
    }

}
