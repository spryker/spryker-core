<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSetStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductSetStorage\Persistence\ProductSetStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSetStorage\Communication\ProductSetStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSetStorage\Business\ProductSetStorageFacadeInterface getFacade()
 */
class ProductSetUrlStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productSetIds = $this->getValidProductSetIds($eventTransfers);
        if (empty($productSetIds)) {
            return;
        }

        $this->getFacade()->publish($productSetIds);
    }

    /**
     * @param array $eventTransfers
     *
     * @return array
     */
    protected function getValidProductSetIds(array $eventTransfers)
    {
        $validEventTransfers = $this->getFactory()->getEventBehaviorFacade()->getEventTransfersByModifiedColumns(
            $eventTransfers,
            [
                SpyUrlTableMap::COL_URL,
                SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET,
            ]
        );

        return $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($validEventTransfers, SpyUrlTableMap::COL_FK_RESOURCE_PRODUCT_SET);
    }
}
