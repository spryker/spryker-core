<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductLocalizedAttributesListener extends AbstractProductConcretePageSearchListener
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
        $this->preventTransaction();
        $productIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT);

        if ($eventName === ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_CREATE
            || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_UPDATE
            || $eventName === ProductEvents::ENTITY_SPY_PRODUCT_LOCALIZED_ATTRIBUTES_DELETE
        ) {
            $this->publish($productIds);
        }
    }
}
