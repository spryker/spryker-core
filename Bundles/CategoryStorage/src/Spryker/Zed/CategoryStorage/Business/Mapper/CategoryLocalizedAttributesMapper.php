<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;

class CategoryLocalizedAttributesMapper implements CategoryLocalizedAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]|\ArrayObject $categoryLocalizedAttributesTransfers
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    public function mapCategoryLocalizedAttributesTransfersToCategoryNodeStorageTransferForLocale(
        ArrayObject $categoryLocalizedAttributesTransfers,
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        string $localeName
    ): CategoryNodeStorageTransfer {
        $categoryLocalizedAttributesTransfer = $this->findCategoryLocalizedAttributesTransferForLocale(
            $categoryLocalizedAttributesTransfers,
            $localeName
        );

        if (!$categoryLocalizedAttributesTransfer) {
            return $categoryNodeStorageTransfer;
        }

        if ($categoryLocalizedAttributesTransfer->getImage()) {
            $categoryNodeStorageTransfer->setImage($categoryLocalizedAttributesTransfer->getImageOrFail()->getNameOrFail());
        }

        return $categoryNodeStorageTransfer
            ->setUrl($categoryLocalizedAttributesTransfer->getUrl())
            ->setName($categoryLocalizedAttributesTransfer->getName())
            ->setMetaTitle($categoryLocalizedAttributesTransfer->getMetaTitle())
            ->setMetaDescription($categoryLocalizedAttributesTransfer->getMetaDescription())
            ->setMetaKeywords($categoryLocalizedAttributesTransfer->getMetaKeywords());
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]|\ArrayObject $categoryLocalizedAttributesTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer|null
     */
    protected function findCategoryLocalizedAttributesTransferForLocale(
        ArrayObject $categoryLocalizedAttributesTransfers,
        string $localeName
    ): ?CategoryLocalizedAttributesTransfer {
        foreach ($categoryLocalizedAttributesTransfers as $categoryLocalizedAttributesTransfer) {
            if ($localeName === $categoryLocalizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail()) {
                return $categoryLocalizedAttributesTransfer;
            }
        }

        return null;
    }
}
