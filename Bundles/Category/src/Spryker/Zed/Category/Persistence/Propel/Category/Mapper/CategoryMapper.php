<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Category\Mapper;

use Generated\Shared\Transfer\CategoryCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryMapper
{
    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    public function mapCategoryTransferToCategoryEntity(
        CategoryTransfer $categoryTransfer,
        SpyCategory $categoryEntity
    ): SpyCategory {
        return $categoryEntity->fromArray($categoryTransfer->modifiedToArray());
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function mapCategoryEntityToCategoryTransfer(
        SpyCategory $categoryEntity,
        CategoryTransfer $categoryTransfer
    ): CategoryTransfer {
        return $categoryTransfer->fromArray($categoryEntity->toArray(), true);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Category\Persistence\SpyCategory> $categoryEntityCollection
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionResponseTransfer
     */
    public function mapCategoryEntityCollectionToCategoryCollectionResponseTransfer(
        ObjectCollection $categoryEntityCollection,
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): CategoryCollectionResponseTransfer {
        foreach ($categoryEntityCollection as $categoryEntity) {
            $categoryCollectionResponseTransfer->addCategory($this->mapCategoryEntityToCategoryTransfer($categoryEntity, new CategoryTransfer()));
        }

        return $categoryCollectionResponseTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Category\Persistence\SpyCategory> $categoryEntityCollection
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryEntityCollectionToCategoryCollectionTransfer(
        ObjectCollection $categoryEntityCollection,
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): CategoryCollectionTransfer {
        foreach ($categoryEntityCollection as $categoryEntity) {
            $categoryCollectionTransfer->addCategory($this->mapCategoryEntityToCategoryTransfer($categoryEntity, new CategoryTransfer()));
        }

        return $categoryCollectionTransfer;
    }
}
