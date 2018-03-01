<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductGroupStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductGroup\Persistence\Map\SpyProductAbstractGroupTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductGroupStorage\Persistence\ProductGroupStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductGroupStorage\Communication\ProductGroupStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductGroupStorage\Business\ProductGroupStorageFacadeInterface getFacade()
 */
class ProductAbstractGroupStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyProductAbstractGroupTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->getFacade()->publish($productAbstractIds);
    }
}
