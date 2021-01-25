<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @deprecated Use {@link \Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener\PriceProductDefaultProductPagePublishListener} instead.
 *
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductPagePriceProductStoreSearchListener extends AbstractProductPageSearchListener implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->preventTransaction();
        $priceProductIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventEntityTransfers, SpyPriceProductStoreTableMap::COL_FK_PRICE_PRODUCT);
        $productAbstractIds = $this->getQueryContainer()->queryAllProductAbstractIdsByPriceProductIds($priceProductIds)->find()->getData();

        $this->publish($productAbstractIds);
    }
}
