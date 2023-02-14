<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Business\Event;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\ProductCategoryCollectionTransfer;
use Spryker\Zed\ProductCategory\Dependency\Facade\ProductCategoryToEventInterface;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\ProductCategory\Persistence\ProductCategoryRepositoryInterface;

class ProductCategoryEventTrigger implements ProductCategoryEventTriggerInterface
{
    /**
     * @see \Orm\Zed\Product\Persistence\Map\SpyProductTableMap::COL_FK_PRODUCT_ABSTRACT
     *
     * @var string
     */
    protected const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';

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
        $productCategoryCollectionTransfer = $this->productCategoryRepository->findProductCategoryChildrenMappingsByCategoryNodeId(
            $categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail(),
        );

        if (!count($productCategoryCollectionTransfer->getProductCategories())) {
            return;
        }

        $this->eventFacade->triggerBulk(
            ProductCategoryEvents::PRODUCT_CONCRETE_UPDATE,
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

            $eventEntityTransfers[$idProductAbstract] = (new EventEntityTransfer())->setForeignKeys([
                static::COL_FK_PRODUCT_ABSTRACT => $idProductAbstract,
            ]);
        }

        return $eventEntityTransfers;
    }
}
