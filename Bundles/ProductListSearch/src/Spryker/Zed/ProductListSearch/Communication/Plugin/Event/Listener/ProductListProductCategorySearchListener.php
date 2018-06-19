<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Spryker\Shared\ProductListSearch\ProductListSearchConfig;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductListSearch\Communication\ProductListSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListSearch\Business\ProductListSearchFacadeInterface getFacade()
 */
class ProductListProductCategorySearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $this->preventTransaction();
        $categoryIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductListCategoryTableMap::COL_FK_CATEGORY);

        $this->getFactory()->getProductPageSearchFacade()->refresh(
            $this->getFacade()->getProductAbstractIdsByCategoryIds($categoryIds),
            [ProductListSearchConfig::PLUGIN_PRODUCT_LIST_DATA]
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return int[]
     */
    protected function getProductListProductCategoryIds($eventTransfers, $eventName): array
    {
        if ($eventName === ProductListEvents::ENTITY_SPY_PRODUCT_LIST_CATEGORY_CREATE) {
            return $this->getFactory()
                ->getEventBehaviorFacade()
                ->getEventTransferIds($eventTransfers);
        }

        return $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductListCategoryTableMap::COL_FK_CATEGORY);
    }
}
