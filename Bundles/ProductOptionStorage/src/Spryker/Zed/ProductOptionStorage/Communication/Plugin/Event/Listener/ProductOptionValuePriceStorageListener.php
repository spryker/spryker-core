<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductOption\Persistence\Map\SpyProductOptionValuePriceTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductOptionStorage\Persistence\ProductOptionStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductOptionStorage\Communication\ProductOptionStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductOptionStorage\Business\ProductOptionStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOptionStorage\ProductOptionStorageConfig getConfig()
 */
class ProductOptionValuePriceStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $productOptionValueIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventEntityTransfers, SpyProductOptionValuePriceTableMap::COL_FK_PRODUCT_OPTION_VALUE);

        $productAbstractIds = $this->getQueryContainer()
            ->queryProductAbstractIdsByProductValueOptionByIds($productOptionValueIds)
            ->find()
            ->getData();

        $this->getFacade()->publish($productAbstractIds);
    }
}
