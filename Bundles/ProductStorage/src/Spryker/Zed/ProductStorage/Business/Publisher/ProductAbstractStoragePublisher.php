<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductStorage\Business\Publisher;

use Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface;

class ProductAbstractStoragePublisher implements ProductAbstractStoragePublisherInterface
{
    /**
     * @uses \Orm\Zed\Product\Persistence\Map\SpyProductLocalizedAttributesTableMap::COL_FK_PRODUCT
     *
     * @var string
     */
    protected const COL_FK_PRODUCT = 'spy_product_localized_attributes.fk_product';

    /**
     * @param \Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductStorage\Business\Storage\ProductAbstractStorageWriterInterface $productAbstractStorageWriter
     * @param \Spryker\Zed\ProductStorage\Persistence\ProductStorageRepositoryInterface $productStorageRepository
     */
    public function __construct(
        protected ProductStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        protected ProductAbstractStorageWriterInterface $productAbstractStorageWriter,
        protected ProductStorageRepositoryInterface $productStorageRepository
    ) {
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function publishByProductLocalizedAttributesEvents(array $eventEntityTransfers, string $eventName): void
    {
        $productIds = $this->eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, static::COL_FK_PRODUCT);

        if (!$productIds) {
            return;
        }

        $productAbstractIds = $this->productStorageRepository->getProductAbstractIdsByProductIds($productIds);

        if (!$productAbstractIds) {
            return;
        }

        $this->productAbstractStorageWriter->publish($productAbstractIds);
    }
}
