<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductResourceAliasStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProduct\Dependency\PriceProductEvents;

/**
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Communication\PriceProductResourceAliasStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductResourceAliasStorage\Business\PriceProductResourceAliasStorageFacadeInterface getFacade()
 */
class PriceProductConcreteMappingResourceStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $ids = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventTransfers);

        if ($eventName == PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_CREATE) {
            $this->getFacade()->updatePriceProductConcreteStorageSkusByProductConcreteIds($ids);
        }

        if ($eventName == PriceProductEvents::ENTITY_SPY_PRICE_PRODUCT_STORE_CREATE) {
            $this->getFacade()->updatePriceProductConcreteStorageSkusByStoreIds($ids);
        }
    }
}
