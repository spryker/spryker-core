<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractStoreTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 */
class ProductConcreteProductAbstractStoreStorageListener extends AbstractProductConcreteStorageListener implements EventBulkHandlerInterface
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

        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductAbstractStoreTableMap::COL_FK_PRODUCT_ABSTRACT);
        $productConcreteIds = $this->getProductConcreteIds($productAbstractIds);

        if (!$productConcreteIds) {
            return;
        }

        $this->publish($productConcreteIds);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    protected function getProductConcreteIds(array $productAbstractIds)
    {
        return $this->getQueryContainer()->queryProductIdsByProductAbstractIds($productAbstractIds)->find()->getData();
    }
}
