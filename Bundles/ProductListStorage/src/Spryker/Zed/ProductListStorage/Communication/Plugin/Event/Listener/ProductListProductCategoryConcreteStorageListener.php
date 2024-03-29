<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductList\Persistence\Map\SpyProductListCategoryTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductList\Dependency\ProductListEvents;

/**
 * @method \Spryker\Zed\ProductListStorage\Communication\ProductListStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductListStorage\Business\ProductListStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductListStorage\ProductListStorageConfig getConfig()
 */
class ProductListProductCategoryConcreteStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $productAbstractIds = $this->getFacade()->getProductAbstractIdsByCategoryIds(
            $this->getProductListCategoryIds($eventEntityTransfers, $eventName),
        );

        $this->getFacade()->publishProductConcrete(
            $this->getFacade()->findProductConcreteIdsByProductAbstractIds($productAbstractIds),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventTransfers
     * @param string $eventName
     *
     * @return array<int>
     */
    protected function getProductListCategoryIds(array $eventTransfers, string $eventName): array
    {
        if ($eventName === ProductListEvents::PRODUCT_LIST_CATEGORY_PUBLISH) {
            return $this->getFactory()
                ->getEventBehaviorFacade()
                ->getEventTransferIds($eventTransfers);
        }

        return $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductListCategoryTableMap::COL_FK_CATEGORY);
    }
}
