<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Event;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToEventInterface;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class ProductCategoryEventTrigger implements ProductCategoryEventTriggerInterface
{
    /**
     * @var string
     */
    protected const KEY_FK_CATEGORY = 'fk_category';

    /**
     * @var \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface
     */
    protected ProductCategoryRepositoryInterface $productCategoryRepository;

    /**
     * @var \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToEventInterface
     */
    protected ProductCategoryToEventInterface $eventFacade;

    /**
     * @param \Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface $productCategoryRepository
     * @param \Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToEventInterface $eventFacade
     */
    public function __construct(
        ProductCategoryRepositoryInterface $productCategoryRepository,
        ProductCategoryToEventInterface $eventFacade
    ) {
        $this->productCategoryRepository = $productCategoryRepository;
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerProductUpdateEventsForCategory(CategoryTransfer $categoryTransfer): void
    {
        $productCategoryCollectionTransfer = $this->productCategoryRepository->findProductCategoryChildrenMappingsByCategoryNodeIds(
            [$categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail()],
        );

        if (!count($productCategoryCollectionTransfer->getProductCategories())) {
            return;
        }

        $this->eventFacade->triggerBulk(
            ProductCategoryEvents::PRODUCT_ABSTRACT_PUBLISH,
            array_values($this->getEventEntityTransfersIndexedByIdProductAbstract($productCategoryCollectionTransfer)),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\EventEntityTransfer>
     */
    protected function getEventEntityTransfersIndexedByIdProductAbstract(
        ProductCategoryCollectionTransfer $productCategoryCollectionTransfer
    ): array {
        $eventEntityTransfers = [];
        foreach ($productCategoryCollectionTransfer->getProductCategories() as $productCategoryTransfer) {
            $idProductAbstract = $productCategoryTransfer->getFkProductAbstractOrFail();
            if (array_key_exists($idProductAbstract, $eventEntityTransfers)) {
                continue;
            }

            $eventEntityTransfers[$idProductAbstract] = (new EventEntityTransfer())->setId($idProductAbstract);
        }

        return $eventEntityTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function triggerProductAbstractUpdateEvents(array $eventEntityTransfers): void
    {
        $categoryIds = $this->extractCategoryIds($eventEntityTransfers);
        if (!$categoryIds) {
            return;
        }

        $productCategoryCollectionTransfer = $this->productCategoryRepository->findProductCategoryByCategoryIds($categoryIds);
        if ($productCategoryCollectionTransfer->getProductCategories()->count() === 0) {
            return;
        }

        $this->eventFacade->triggerBulk(
            ProductCategoryEvents::PRODUCT_ABSTRACT_UPDATE,
            array_map(
                fn (ProductCategoryTransfer $productCategoryTransfer) => (new EventEntityTransfer())->setId($productCategoryTransfer->getFkProductAbstractOrFail()),
                $productCategoryCollectionTransfer->getProductCategories()->getArrayCopy(),
            ),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return array<int>
     */
    protected function extractCategoryIds(array $eventEntityTransfers): array
    {
        $categoryIds = [];

        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            // checking if event has foreign key for category, the format is {table_name}.fk_category
            $foreignKeys = $eventEntityTransfer->getForeignKeys();
            $key = sprintf('%s.%s', $eventEntityTransfer->getName(), static::KEY_FK_CATEGORY);
            if (!empty($foreignKeys[$key])) {
                $categoryIds[$foreignKeys[$key]] = $foreignKeys[$key];

                continue;
            }

            if ($eventEntityTransfer->getId() !== null) {
                $categoryIds[$eventEntityTransfer->getId()] = $eventEntityTransfer->getId();
            }
        }

        return array_values($categoryIds);
    }
}
