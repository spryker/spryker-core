<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductImageSetListener extends AbstractProductConcretePageSearchListener
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
        $productConcreteIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyProductImageSetTableMap::COL_FK_PRODUCT);

        $this->publish($productConcreteIds);
    }
}
