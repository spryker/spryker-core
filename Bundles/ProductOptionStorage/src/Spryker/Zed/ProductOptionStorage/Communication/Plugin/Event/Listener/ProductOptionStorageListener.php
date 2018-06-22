<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductAbstractProductOptionGroupTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOptionStorage\Communication\ProductOptionStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacadeInterface getFacade()
 */
class ProductOptionStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductAbstractProductOptionGroupTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->getFacade()->publish($productAbstractIds);
    }
}
