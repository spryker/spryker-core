<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryMapper
{
    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\ProductCategory\Persistence\SpyProductCategory[] $productCategoryEntities
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollection(
        ObjectCollection $productCategoryEntities,
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): CategoryCollectionTransfer {
        foreach ($productCategoryEntities as $productCategoryEntity) {
            /** @var \Orm\Zed\Category\Persistence\SpyCategory|null $categoryEntity */
            $categoryEntity = $productCategoryEntity->getSpyCategory();

            if (!$categoryEntity) {
                continue;
            }

            $categoryTransfer = (new CategoryTransfer())->fromArray($categoryEntity->toArray(), true);
            $categoryTransfer = $this->addLocalizedAttributesToCategory($categoryEntity, $categoryTransfer);

            foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
                $categoryTransfer->fromArray($localizedAttribute->toArray(), true);
            }

            $categoryCollectionTransfer->addCategory($categoryTransfer);
        }

        return $categoryCollectionTransfer;
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
        /** @var \Orm\Zed\Category\Persistence\SpyCategoryNode|null $categoryNodeEntity */
        $categoryNodeEntity = $categoryEntity->getNodes()->getIterator()->current();

        $categoryTransfer = $categoryTransfer
            ->fromArray($categoryEntity->toArray(), true)
            ->setCategoryNode((new NodeTransfer())->fromArray($categoryNodeEntity ? $categoryNodeEntity->toArray() : [], true));

        $categoryTransfer = $this->addLocalizedAttributesToCategory($categoryEntity, $categoryTransfer);

        foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $categoryTransfer->fromArray($localizedAttribute->toArray(), true);
        }

        return $categoryTransfer;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function addLocalizedAttributesToCategory(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        foreach ($categoryEntity->getAttributes() as $attribute) {
            $localeTransfer = (new LocaleTransfer())
                ->fromArray($attribute->getLocale()->toArray(), true);

            $categoryLocalizedAttributesTransfer = (new CategoryLocalizedAttributesTransfer())
                ->fromArray($attribute->toArray(), true)
                ->setLocale($localeTransfer);

            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }

        return $categoryTransfer;
    }
}
