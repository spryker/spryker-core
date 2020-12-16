<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;

class CategoryNodeStorageMapper implements CategoryNodeStorageMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer[] $nodeTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function mapNodeTransfersToCategoryNodeStorageTransfersForLocaleAndStore(array $nodeTransfers, string $localeName, string $storeName): array
    {
        $localizedCategoryNodeStorageTransfers = [];
        foreach ($nodeTransfers as $nodeTransfer) {
            $categoryTransfer = $nodeTransfer->getCategoryOrFail();
            if (!$this->isCategoryHasStoreRelation($categoryTransfer, $storeName)) {
                continue;
            }

            $categoryNodeStorageTransfer = (new CategoryNodeStorageTransfer())
                ->setIdCategory($categoryTransfer->getIdCategory())
                ->setNodeId($nodeTransfer->getIdCategoryNode())
                ->setIsActive($categoryTransfer->getIsActive())
                ->setTemplatePath($categoryTransfer->getCategoryTemplateOrFail()->getTemplatePath())
                ->setOrder($nodeTransfer->getNodeOrder());
            $categoryNodeStorageTransfer = $this->mapCategoryLocalizedAttributesTransfersToCategoryNodeStorageTransferForLocale(
                $categoryTransfer->getLocalizedAttributes(),
                $categoryNodeStorageTransfer,
                $localeName
            );

            $localizedCategoryNodeStorageTransfers[$nodeTransfer->getIdCategoryNode()] = $categoryNodeStorageTransfer;
        }

        return $localizedCategoryNodeStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param string $storeName
     *
     * @return bool
     */
    protected function isCategoryHasStoreRelation(CategoryTransfer $categoryTransfer, string $storeName): bool
    {
        foreach ($categoryTransfer->getStoreRelationOrFail()->getStores() as $storeTransfer) {
            if ($storeTransfer->getName() === $storeName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer[]|\ArrayObject $categoryLocalizedAttributesTransfers
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function mapCategoryLocalizedAttributesTransfersToCategoryNodeStorageTransferForLocale(
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
            $categoryNodeStorageTransfer->setImage($categoryLocalizedAttributesTransfer->getImage()->getName());
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
            if ($localeName === $categoryLocalizedAttributesTransfer->getLocale()->getLocaleName()) {
                return $categoryLocalizedAttributesTransfer;
            }
        }

        return null;
    }
}
