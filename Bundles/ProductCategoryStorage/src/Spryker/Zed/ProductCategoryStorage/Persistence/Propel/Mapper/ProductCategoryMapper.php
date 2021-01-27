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
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategory;
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
            $productCategoryTransfer = $this->mapProductCategoryEntityToProductCategoryTransfer(
                $productCategoryEntity,
                new ProductCategoryTransfer()
            );

            $categoryTransfer = $this->mapCategoryEntityToCategoryTransfer(
                $productCategoryEntity->getSpyCategory(),
                new CategoryTransfer()
            );

            $productCategoryTransfer->setCategory($categoryTransfer);
            $productCategoryTransfers[] = $productCategoryTransfer;
        }

        return $productCategoryTransfers;
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory $productCategoryEntity
     * @param \Generated\Shared\Transfer\ProductCategoryTransfer $productCategoryTransfer
     *
     * @return \Generated\Shared\Transfer\ProductCategoryTransfer
     */
    protected function mapProductCategoryEntityToProductCategoryTransfer(
        SpyProductCategory $productCategoryEntity,
        ProductCategoryTransfer $productCategoryTransfer
    ): ProductCategoryTransfer {
        return $productCategoryTransfer->fromArray($productCategoryEntity->toArray(), true);
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
                ->addNode($this->mapCategoryNodeEntityToNodeTransfer($categoryNodeEntity, new NodeTransfer()));
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function mapCategoryNodeEntityToNodeTransfer(
        SpyCategoryNode $categoryNodeEntity,
        NodeTransfer $nodeTransfer
    ): NodeTransfer {
        return $nodeTransfer->fromArray($categoryNodeEntity->toArray(), true);
    }
}
