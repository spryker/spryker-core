<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\ProductCategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Propel\Runtime\Collection\ObjectCollection;

class ProductCategoryMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductCategory\Persistence\SpyProductCategory[] $productCategoryEntities
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer[] $productCategoryTransfers
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer[]
     */
    public function mapProductCategoryEntitiesToProductCategoryTransfers(
        ObjectCollection $productCategoryEntities,
        array $productCategoryTransfers
    ): array {
        foreach ($productCategoryEntities as $productCategoryEntity) {
            $categoryTransfer = $this->mapCategoryEntityToCategoryTransfer(
                $productCategoryEntity->getSpyCategory(),
                new CategoryTransfer()
            );

            $productCategoryTransfers[] = (new ProductCategoryTransfer())
                ->fromArray($productCategoryEntity->toArray(), true)
                ->setCategory($categoryTransfer);
        }

        return $productCategoryTransfers;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $productCategoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapCategoryEntityToCategoryTransfer(
        SpyCategory $productCategoryEntity,
        CategoryTransfer $categoryTransfer
    ): CategoryTransfer {
        $categoryTransfer
            ->fromArray($productCategoryEntity->toArray(), true)
            ->setNodeCollection(new NodeCollectionTransfer());

        foreach ($productCategoryEntity->getNodes() as $categoryNodeEntity) {
            $categoryTransfer->getNodeCollection()
                ->addNode((new NodeTransfer())->fromArray($categoryNodeEntity->toArray(), true));
        }

        return $categoryTransfer;
    }
}
