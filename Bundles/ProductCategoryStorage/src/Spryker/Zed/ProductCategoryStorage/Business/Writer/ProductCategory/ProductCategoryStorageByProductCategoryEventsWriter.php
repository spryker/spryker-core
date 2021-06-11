<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategory;

use Orm\Zed\ProductCategory\Persistence\Map\SpyProductCategoryTableMap;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface;

class ProductCategoryStorageByProductCategoryEventsWriter implements ProductCategoryStorageByProductCategoryEventsWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface
     */
    protected $productCategoryStorageWriter;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface $productCategoryStorageWriter
     */
    public function __construct(
        ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductCategoryStorageWriterInterface $productCategoryStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productCategoryStorageWriter = $productCategoryStorageWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductCategoryPublishingEvents(array $eventEntityTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);

        $this->productCategoryStorageWriter->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByProductCategoryEvents(array $eventEntityTransfers): void
    {
        $productAbstractIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyProductCategoryTableMap::COL_FK_PRODUCT_ABSTRACT
        );

        $this->productCategoryStorageWriter->writeCollection($productAbstractIds);
    }
}
