<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImageStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductImageStorage\Persistence\ProductImageStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductImageStorage\Communication\ProductImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductImageStorage\Business\ProductImageStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductImageStorage\ProductImageStorageConfig getConfig()
 */
class ProductConcreteImageSetStorageUnpublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritDoc}
     *
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
        $productIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyProductImageSetTableMap::COL_FK_PRODUCT);

        $this->getFacade()->unpublishProductConcreteImages($productIds);
    }
}
