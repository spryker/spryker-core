<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductSearch\Persistence\Map\SpyProductSearchTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    /**
     * @param array $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $productIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys(
                $eventEntityTransfers,
                SpyProductSearchTableMap::COL_FK_PRODUCT
            );

        $productAbstractIds = $this->getQueryContainer()
            ->queryProductAbstractIdsByProductIds($productIds)
            ->find()
            ->getData();

        $this->publish($productAbstractIds);
    }
}
