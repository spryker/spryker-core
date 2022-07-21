<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeUrlCriteriaTransfer;
use Generated\Shared\Transfer\NodeCollectionTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryLocalizedAttributesCategoryNodeRelationExpander implements CategoryNodeRelationExpanderInterface
{
    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $categoryNodeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\NodeCollectionTransfer
     */
    public function expandNodeCollectionWithRelations(
        NodeCollectionTransfer $categoryNodeCollectionTransfer
    ): NodeCollectionTransfer {
        $categoryIds = $this->extractCategoryIdsFromCategoryNodeCollection($categoryNodeCollectionTransfer);
        if ($categoryIds === []) {
            return $categoryNodeCollectionTransfer;
        }

        $categoryNodeIds = $this->extractCategoryNodeIdsFromCategoryNodeCollection($categoryNodeCollectionTransfer);
        if ($categoryNodeIds === []) {
            return $categoryNodeCollectionTransfer;
        }

        $groupedCategoryLocalizedAttributesTransfers = $this->categoryRepository->getCategoryAttributesByCategoryIdsGroupByIdCategory($categoryIds);

        $categoryNodeUrlTransfers = $this->categoryRepository->getCategoryNodeUrls(
            (new CategoryNodeUrlCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds),
        );
        $groupedCategoryNodeUrlTransfers = $this->getCategoryNodeUrlsGroupedByIdCategoryNode($categoryNodeUrlTransfers);

        foreach ($categoryNodeCollectionTransfer->getNodes() as $categoryNodeTransfer) {
            $this->expandCategoryNodeWithCategoryLocalizedAttributes(
                $categoryNodeTransfer,
                $groupedCategoryLocalizedAttributesTransfers,
                $groupedCategoryNodeUrlTransfers,
            );
        }

        return $categoryNodeCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param array<int, array<\Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer>> $groupedCategoryLocalizedAttributesTransfers
     * @param array<int, array<\Generated\Shared\Transfer\UrlTransfer>> $groupedCategoryNodeUrlTransfers
     *
     * @return void
     */
    protected function expandCategoryNodeWithCategoryLocalizedAttributes(
        NodeTransfer $categoryNodeTransfer,
        array $groupedCategoryLocalizedAttributesTransfers,
        array $groupedCategoryNodeUrlTransfers
    ): void {
        $categoryTransfer = $categoryNodeTransfer->getCategoryOrFail();
        if (!isset($groupedCategoryLocalizedAttributesTransfers[$categoryTransfer->getIdCategoryOrFail()])) {
            return;
        }

        $categoryLocalizedAttributes = $groupedCategoryLocalizedAttributesTransfers[$categoryTransfer->getIdCategoryOrFail()];

        if (isset($groupedCategoryNodeUrlTransfers[$categoryNodeTransfer->getIdCategoryNodeOrFail()])) {
            $categoryLocalizedAttributes = $this->expandCategoryLocalizedAttributesWithCategoryNodeUrls(
                $categoryLocalizedAttributes,
                $groupedCategoryNodeUrlTransfers[$categoryNodeTransfer->getIdCategoryNodeOrFail()],
            );
        }

        $categoryTransfer->setLocalizedAttributes(
            new ArrayObject($categoryLocalizedAttributes),
        );
    }

    /**
     * @param array<\Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer> $categoryLocalizedAttributes
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $categoryNodeUrlTransfers
     *
     * @return array<\Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer>
     */
    protected function expandCategoryLocalizedAttributesWithCategoryNodeUrls(
        array $categoryLocalizedAttributes,
        array $categoryNodeUrlTransfers
    ): array {
        $indexedCategoryNodeUrlTransfers = $this->getCategoryNodeUrlsIndexedByIdLocale($categoryNodeUrlTransfers);

        foreach ($categoryLocalizedAttributes as $categoryLocalizedAttribute) {
            $localeTransfer = $categoryLocalizedAttribute->getLocaleOrFail();
            if (isset($indexedCategoryNodeUrlTransfers[$localeTransfer->getIdLocaleOrFail()])) {
                $categoryLocalizedAttribute->setUrl(
                    $indexedCategoryNodeUrlTransfers[$localeTransfer->getIdLocaleOrFail()]->getUrlOrFail(),
                );
            }
        }

        return $categoryLocalizedAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $categoryNodeCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractCategoryIdsFromCategoryNodeCollection(NodeCollectionTransfer $categoryNodeCollectionTransfer): array
    {
        $categoryIds = [];
        foreach ($categoryNodeCollectionTransfer->getNodes() as $categoryNodeTransfer) {
            $categoryIds[] = $categoryNodeTransfer->getFkCategoryOrFail();
        }

        return $categoryIds;
    }

    /**
     * @param \Generated\Shared\Transfer\NodeCollectionTransfer $categoryNodeCollectionTransfer
     *
     * @return array<int>
     */
    protected function extractCategoryNodeIdsFromCategoryNodeCollection(
        NodeCollectionTransfer $categoryNodeCollectionTransfer
    ): array {
        $categoryNodeIds = [];
        foreach ($categoryNodeCollectionTransfer->getNodes() as $categoryNodeTransfer) {
            $categoryNodeIds[] = $categoryNodeTransfer->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $categoryNodeUrlTransfers
     *
     * @return array<int, array<\Generated\Shared\Transfer\UrlTransfer>>
     */
    protected function getCategoryNodeUrlsGroupedByIdCategoryNode(array $categoryNodeUrlTransfers): array
    {
        $groupedCategoryNodeUrlTransfers = [];
        foreach ($categoryNodeUrlTransfers as $urlTransfer) {
            $groupedCategoryNodeUrlTransfers[$urlTransfer->getFkResourceCategorynodeOrFail()][] = $urlTransfer;
        }

        return $groupedCategoryNodeUrlTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\UrlTransfer> $categoryNodeUrlTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\UrlTransfer>
     */
    protected function getCategoryNodeUrlsIndexedByIdLocale(array $categoryNodeUrlTransfers): array
    {
        $indexedCategoryNodeUrlTransfers = [];
        foreach ($categoryNodeUrlTransfers as $categoryNodeUrlTransfer) {
            $indexedCategoryNodeUrlTransfers[$categoryNodeUrlTransfer->getFkLocaleOrFail()] = $categoryNodeUrlTransfer;
        }

        return $indexedCategoryNodeUrlTransfers;
    }
}
