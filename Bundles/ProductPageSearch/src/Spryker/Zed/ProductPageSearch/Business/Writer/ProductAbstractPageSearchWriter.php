<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\HydrateEventsRequestTransfer;
use Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface;
use Spryker\Zed\ProductPageSearch\Business\Reader\CategoryReaderInterface;
use Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface;
use Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface;

class ProductAbstractPageSearchWriter implements ProductAbstractPageSearchWriterInterface
{
    /**
     * @uses \Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_IMAGE_SET = 'spy_product_image_set_to_product_image.fk_product_image_set';

    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryStoreTableMap::COL_FK_CATEGORY
     *
     * @var string
     */
    protected const COL_FK_CATEGORY = 'spy_category_store.fk_category';

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface
     */
    protected ProductPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface
     */
    protected ProductPageSearchRepositoryInterface $productPageSearchRepository;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface
     */
    protected ProductAbstractPagePublisherInterface $productAbstractPagePublisher;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Business\Reader\CategoryReaderInterface
     */
    protected CategoryReaderInterface $categoryReader;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Facade\ProductPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade
     * @param \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface $productPageSearchRepository
     * @param \Spryker\Zed\ProductPageSearch\Business\Publisher\ProductAbstractPagePublisherInterface $productAbstractPagePublisher
     * @param \Spryker\Zed\ProductPageSearch\Business\Reader\CategoryReaderInterface $categoryReader
     */
    public function __construct(
        ProductPageSearchToEventBehaviorFacadeInterface $eventBehaviorFacade,
        ProductPageSearchRepositoryInterface $productPageSearchRepository,
        ProductAbstractPagePublisherInterface $productAbstractPagePublisher,
        CategoryReaderInterface $categoryReader
    ) {
        $this->eventBehaviorFacade = $eventBehaviorFacade;
        $this->productPageSearchRepository = $productPageSearchRepository;
        $this->productAbstractPagePublisher = $productAbstractPagePublisher;
        $this->categoryReader = $categoryReader;
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductAbstractPageSearchCollectionByProductImageSetToProductImageEvents(array $eventEntityTransfers): void
    {
        $hydrateEventsResponseTransfer = $this->eventBehaviorFacade->hydrateEventDataTransfer(
            (new HydrateEventsRequestTransfer())
                ->setEventEntities(new ArrayObject($eventEntityTransfers))
                ->setForeignKeyName(static::COL_FK_PRODUCT_IMAGE_SET),
        );

        $productAbstractIdTimestampMap = $this->productPageSearchRepository->getProductAbstractIdsByProductImageSetIds($hydrateEventsResponseTransfer->getForeignKeyTimestampMap());
        $productAbstractIdTimestampMapToUpdate = $this->productPageSearchRepository->getRelevantProductAbstractIdsToUpdate($productAbstractIdTimestampMap);

        $this->productAbstractPagePublisher->publishWithTimestamps($productAbstractIdTimestampMapToUpdate);
    }

    /**
     * @param list<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeProductAbstractPageSearchCollectionByCategoryStoreEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->eventBehaviorFacade->getEventTransferForeignKeys(
            $eventEntityTransfers,
            static::COL_FK_CATEGORY,
        );

        if (!$categoryIds) {
            return;
        }

        $relatedCategoryIds = $this->categoryReader->getRelatedCategoryIdsByCategoryIds($categoryIds);
        $productAbstractIds = $this->productPageSearchRepository->getProductAbstractIdsByCategoryIds($relatedCategoryIds);

        $this->productAbstractPagePublisher->publish($productAbstractIds);
    }
}
