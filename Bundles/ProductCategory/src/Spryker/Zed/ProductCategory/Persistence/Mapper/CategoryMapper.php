<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Persistence\Mapper;

use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryMapper implements CategoryMapperInterface
{
    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $spyCategory
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function mapCategory(SpyCategory $spyCategory, CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        return $categoryTransfer->fromArray($spyCategory->toArray(), true);
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategory $categoryEntity
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function mapLocalizedAttributes(SpyCategory $categoryEntity, CategoryTransfer $categoryTransfer): void
    {
        foreach ($categoryEntity->getAttributes() as $attribute) {
            $localeTransfer = new LocaleTransfer();
            $localeTransfer->fromArray($attribute->getLocale()->toArray(), true);

            $categoryLocalizedAttributesTransfer = new CategoryLocalizedAttributesTransfer();
            $categoryLocalizedAttributesTransfer->fromArray($attribute->toArray(), true);
            $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);

            $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributesTransfer);
        }
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]|\Propel\Runtime\Collection\ObjectCollection $productCategoryEntities
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryCollectionTransfer
     */
    public function mapCategoryCollection(ObjectCollection $productCategoryEntities, CategoryCollectionTransfer $categoryCollectionTransfer): CategoryCollectionTransfer
    {
        foreach ($productCategoryEntities as $productCategoryEntity) {
            /** @var \Orm\Zed\Category\Persistence\SpyCategory|null $categoryEntity */
            $categoryEntity = $productCategoryEntity->getSpyCategory();
            if ($categoryEntity === null) {
                continue;
            }
            $categoryTransfer = $this->mapCategory($categoryEntity, new CategoryTransfer());
            $this->mapLocalizedAttributes($categoryEntity, $categoryTransfer);

            foreach ($categoryTransfer->getLocalizedAttributes() as $localizedAttribute) {
                $categoryTransfer->fromArray($localizedAttribute->toArray(), true);
            }

            $categoryCollectionTransfer->addCategory($categoryTransfer);
        }

        return $categoryCollectionTransfer;
    }
}
