<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface;

class ProductCategoryTreeBuilder implements ProductCategoryTreeBuilderInterface
{
    protected const COLUMN_ID_CATEGORY_NODE = 'id_category_node';
    protected const COLUMN_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    protected const COLUMN_FK_LOCALE = 'fk_locale';
    protected const COLUMN_FK_CATEGORY = 'fk_category';
    protected const COLUMN_CATEGORY_NAME = 'category_name';
    protected const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface
     */
    protected $productCategorySearchRepository;

    /**
     * @param \Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface $productCategorySearchRepository
     */
    public function __construct(ProductCategorySearchRepositoryInterface $productCategorySearchRepository)
    {
        $this->productCategorySearchRepository = $productCategorySearchRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return int[][]
     */
    public function buildProductCategoryTree(LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer): array
    {
        $categoryTree = [];

        $categoryNodeIds = $this->productCategorySearchRepository->getCategoryNodeIdsByLocaleAndStore($localeTransfer, $storeTransfer);
        $categoryNodes = $this->productCategorySearchRepository->getAllCategoriesWithAttributesAndOrderByDescendant();
        $formattedCategoriesByLocaleAndStoreAndNodeIds = $this->formatCategoriesByNodeIdsForLocaleAndStore($categoryNodes);

        foreach ($categoryNodeIds as $idCategoryNode) {
            $categoryTree = $this->buildProductCategoryTreeByIdCategoryNodeForStoreAndLocale(
                $categoryTree,
                $idCategoryNode,
                $formattedCategoriesByLocaleAndStoreAndNodeIds[$storeTransfer->getName()][$localeTransfer->getIdLocale()][$idCategoryNode] ?? []
            );
        }

        return $categoryTree;
    }

    /**
     * @param int[] $categoryIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string[][]
     */
    public function buildProductCategoryTreeNames(array $categoryIds, LocaleTransfer $localeTransfer): array
    {
        $categoryNames = [];
        $categoryAttributes = $this->productCategorySearchRepository
            ->getCategoryAttributesByLocale($categoryIds, $localeTransfer);

        $categoryNames[$localeTransfer->getIdLocale()] = [];

        foreach ($categoryAttributes as $categoryAttribute) {
            $idCategory = (int)$categoryAttribute[static::COLUMN_FK_CATEGORY];
            $categoryName = $categoryAttribute[static::COLUMN_CATEGORY_NAME];

            $categoryNames[$localeTransfer->getIdLocale()][$idCategory] = $categoryName;
        }

        return $categoryNames;
    }

    /**
     * @param int[][] $categoryTree
     * @param int $idCategoryNode
     * @param array $categories
     *
     * @return int[][]
     */
    protected function buildProductCategoryTreeByIdCategoryNodeForStoreAndLocale(
        array $categoryTree,
        int $idCategoryNode,
        array $categories
    ): array {
        $categoryTree[$idCategoryNode] = [];

        foreach ($categories as $category) {
            $idNode = (int)$category[static::COLUMN_ID_CATEGORY_NODE];

            if (!in_array($idNode, $categoryTree[$idCategoryNode])) {
                $categoryTree[$idCategoryNode][] = $idNode;
            }
        }

        return $categoryTree;
    }

    /**
     * @param array $categoryNodes
     *
     * @return array
     */
    protected function formatCategoriesByNodeIdsForLocaleAndStore(array $categoryNodes): array
    {
        $categories = [];

        foreach ($categoryNodes as $categoryNode) {
            $categories[$categoryNode[static::COLUMN_STORE_NAME]][$categoryNode[static::COLUMN_FK_LOCALE]][$categoryNode[static::COLUMN_FK_CATEGORY_NODE_DESCENDANT]][] = $categoryNode;
        }

        return $categories;
    }
}
