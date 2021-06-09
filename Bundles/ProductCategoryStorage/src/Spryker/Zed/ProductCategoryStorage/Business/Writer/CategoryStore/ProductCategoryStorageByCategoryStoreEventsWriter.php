<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Business\Writer\CategoryStore;

use Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap;
use Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface;
use Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface;
use Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface;

class ProductCategoryStorageByCategoryStoreEventsWriter implements ProductCategoryStorageByCategoryStoreEventsWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface
     */
    protected $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface
     */
    protected $productAbstractReader;

    /**
     * @var \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface
     */
    protected $productCategoryStorageWriter;

    /**
     * @param \Spryker\Zed\ProductCategoryStorage\Dependency\Facade\ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Reader\ProductAbstractReaderInterface $productAbstractReader
     * @param \Spryker\Zed\ProductCategoryStorage\Business\Writer\ProductCategoryStorageWriterInterface $productCategoryStorageWriter
     */
    public function __construct(
        ProductCategoryStorageToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductAbstractReaderInterface $productAbstractReader,
        ProductCategoryStorageWriterInterface $productCategoryStorageWriter
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productAbstractReader = $productAbstractReader;
        $this->productCategoryStorageWriter = $productCategoryStorageWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            SpyCategoryStoreTableMap::COL_FK_CATEGORY
        );

        $productAbstractIds = $this->productAbstractReader->getProductAbstractIdsByCategoryIds($categoryIds);

        $this->productCategoryStorageWriter->writeCollection($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     *
     * @return void
     */
    public function writeCollectionByCategoryStorePublishingEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferIds($eventEntityTransfers);
        $productAbstractIds = $this->productAbstractReader->getProductAbstractIdsByCategoryIds($categoryIds);

        $this->productCategoryStorageWriter->writeCollection($productAbstractIds);
    }
}
