<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Mapper;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\NodeTransfer;

class CategoryNodeStorageMapper implements CategoryNodeStorageMapperInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapperInterface
     */
    protected $categoryLocalizedAttributesMapper;

    /**
     * @param \Spryker\Zed\CategoryStorage\Business\Mapper\CategoryLocalizedAttributesMapperInterface $categoryLocalizedAttributesMapper
     */
    public function __construct(CategoryLocalizedAttributesMapperInterface $categoryLocalizedAttributesMapper)
    {
        $this->categoryLocalizedAttributesMapper = $categoryLocalizedAttributesMapper;
    }

    /**
     * @param array<\Generated\Shared\Transfer\NodeTransfer> $nodeTransfers
     * @param string $localeName
     * @param string $storeName
     *
     * @return array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function mapNodeTransfersToCategoryNodeStorageTransfersByLocaleAndStore(array $nodeTransfers, string $localeName, string $storeName): array
    {
        $localizedCategoryNodeStorageTransfers = [];
        foreach ($nodeTransfers as $nodeTransfer) {
            $categoryTransfer = $nodeTransfer->getCategoryOrFail();
            if (!$this->isCategoryHasStoreRelation($categoryTransfer, $storeName)) {
                continue;
            }

            $categoryNodeStorageTransfer = $this->createCategoryNodeStorageTransfer($categoryTransfer, $nodeTransfer);
            $categoryNodeStorageTransfer = $this->categoryLocalizedAttributesMapper
                ->mapCategoryLocalizedAttributesTransfersToCategoryNodeStorageTransferForLocale(
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
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\NodeTransfer $nodeTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function createCategoryNodeStorageTransfer(CategoryTransfer $categoryTransfer, NodeTransfer $nodeTransfer): CategoryNodeStorageTransfer
    {
        return (new CategoryNodeStorageTransfer())
            ->setIdCategory($categoryTransfer->getIdCategory())
            ->setNodeId($nodeTransfer->getIdCategoryNode())
            ->setIsActive($categoryTransfer->getIsActive())
            ->setTemplatePath($categoryTransfer->getCategoryTemplateOrFail()->getTemplatePath())
            ->setOrder($nodeTransfer->getNodeOrder());
    }
}
