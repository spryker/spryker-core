<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Propel\Runtime\Collection\ObjectCollection;

class CategoryLocalizedAttributeMapper
{
    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategoryAttribute
     */
    public function mapCategoryLocalizedAttributeTransferToCategoryAttributeEntity(
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer,
        SpyCategoryAttribute $categoryAttributeEntity
    ): SpyCategoryAttribute {
        $categoryAttributeEntity->fromArray($categoryLocalizedAttributesTransfer->modifiedToArray());
        $categoryAttributeEntity->setFkLocale($categoryLocalizedAttributesTransfer->getLocaleOrFail()->getIdLocaleOrFail());

        return $categoryAttributeEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\Category\Persistence\SpyCategoryAttribute> $categoryAttributeEntities
     *
     * @return array<int, array<\Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer>>
     */
    public function mapCategoryAttributeEntitiesToCategoryLocalizedAttributesTransfersGroupedByIdCategory(
        ObjectCollection $categoryAttributeEntities
    ): array {
        $categoryLocalizedAttributesTransfers = [];
        foreach ($categoryAttributeEntities as $categoryAttributeEntity) {
            $categoryLocalizedAttributesTransfers[$categoryAttributeEntity->getFkCategory()][] = $this->mapCategoryAttributeEntityToCategoryLocalizedAttributesTransfer(
                $categoryAttributeEntity,
                new CategoryLocalizedAttributesTransfer(),
            );
        }

        return $categoryLocalizedAttributesTransfers;
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    protected function mapCategoryAttributeEntityToCategoryLocalizedAttributesTransfer(
        SpyCategoryAttribute $categoryAttributeEntity,
        CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer
    ): CategoryLocalizedAttributesTransfer {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($categoryAttributeEntity->getLocale()->toArray(), true);

        $categoryLocalizedAttributesTransfer->fromArray($categoryAttributeEntity->toArray(), true);
        $categoryLocalizedAttributesTransfer->setLocale($localeTransfer);

        return $categoryLocalizedAttributesTransfer;
    }
}
