<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductReview\Persistence\Map\SpyProductReviewTableMap;
use Spryker\Shared\ProductReviewSearch\ProductReviewSearchConfig;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductReviewSearch\Persistence\ProductReviewSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewSearch\Communication\ProductReviewSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductReviewSearch\Business\ProductReviewSearchFacadeInterface getFacade()
 */
class ProductReviewSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productReviewIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductReviewTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->getFacade()->publish($productReviewIds);

        if (!empty($productAbstractIds)) {
            $this->getFactory()->getProductPageSearchFacade()->refresh($productAbstractIds, [ProductReviewSearchConfig::PLUGIN_PRODUCT_PAGE_RATING_DATA]);
        }
    }
}
