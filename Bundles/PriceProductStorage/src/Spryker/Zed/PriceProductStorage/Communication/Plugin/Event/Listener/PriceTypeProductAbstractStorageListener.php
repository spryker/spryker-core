<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\PriceProductStorage\Persistence\PriceProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\PriceProductStorage\Communication\PriceProductStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductStorage\Business\PriceProductStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductStorage\PriceProductStorageConfig getConfig()
 */
class PriceTypeProductAbstractStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $priceTypeIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $productAbstractIds = $this->getQueryContainer()->queryAllProductAbstractIdsByPriceTypeIds($priceTypeIds)->find()->getData();

        if ($eventName === PriceProductEvents::ENTITY_SPY_PRICE_TYPE_CREATE || $eventName === PriceProductEvents::ENTITY_SPY_PRICE_TYPE_UPDATE) {
            $this->getFacade()->publishPriceProductAbstract($productAbstractIds);
        } elseif ($eventName === PriceProductEvents::ENTITY_SPY_PRICE_TYPE_DELETE) {
            $this->getFacade()->unpublishPriceProductAbstract($productAbstractIds);
        }
    }
}
