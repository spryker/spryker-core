<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductStorage\Persistence\ProductStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductStorage\Communication\ProductStorageCommunicationFactory getFactory()
 */
class ProductConcreteProductAbstractUrlStorageListener extends AbstractProductConcreteStorageListener implements EventBulkHandlerInterface
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
        $productAbstractIds = $this->getValidProductIds($eventTransfers);
        if (empty($productAbstractIds)) {
            return;
        }

        $productIds = $this->getQueryContainer()->queryProductIdsByProductAbstractIds($productAbstractIds)->find()->getData();
        $this->publish($productIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    protected function getValidProductIds(array $eventTransfers)
    {
        $validEventTransfers = [];
        foreach ($eventTransfers as $eventTransfer) {
            if (in_array(SpyUrlTableMap::COL_URL, $eventTransfer->getModifiedColumns()) ||
                in_array(SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT, $eventTransfer->getModifiedColumns())
            ) {
                $validEventTransfers[] = $eventTransfer;
            }
        }

        return $this->getFactory()->getUtilSynchronization()->getEventTransferForeignKeys(
            $validEventTransfers,
            SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_ABSTRACT
        );
    }

}
