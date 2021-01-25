<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface;

class ProductCategoryTreeBuilder implements ProductCategoryTreeBuilderInterface
{
    protected const COLUMN_ID_CATEGORY_NODE = 'id_category_node';
    protected const COLUMN_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    protected const COLUMN_FK_LOCALE = 'fk_locale';
    protected const COLUMN_FK_CATEGORY = 'fk_category';
    protected const COLUMN_CATEGORY_NAME = 'category_name';

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
     *
     * @return int[][]
     */
    public function buildProductCategoryTree(LocaleTransfer $localeTransfer): array
    {
        $categoryTree = [];

        $categoryNodeIds = $this->productCategorySearchRepository->getCategoryNodeIdsByLocale($localeTransfer);
        $categoryNodes = $this->productCategorySearchRepository->getAllCategoriesWithAttributesAndOrderByDescendant();
        $formattedCategoriesByLocaleAndNodeIds = $this->formatCategoriesWithLocaleAndNodIds($categoryNodes);

        foreach ($categoryNodeIds as $idCategoryNode) {
            $categoryTree = $this->buildProductCategoryTreeByIdCategoryNode(
                $categoryTree,
                $idCategoryNode,
                $formattedCategoriesByLocaleAndNodeIds[$localeTransfer->getIdLocale()][$idCategoryNode] ?? []
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
    protected function buildProductCategoryTreeByIdCategoryNode(
        array $categoryTree,
        int $idCategoryNode,
        array $categories
    ): array {
        $categoryTree[$idCategoryNode] = [];

        foreach ($categories as $category) {
            $idCategory = (int)$category[static::COLUMN_ID_CATEGORY_NODE];

            if (!in_array($idCategory, $categoryTree[$idCategoryNode])) {
                $categoryTree[$idCategoryNode][] = $idCategory;
            }
        }

        return $categoryTree;
    }

    /**
     * @param array $categoryNodes
     *
     * @return array
     */
    protected function formatCategoriesWithLocaleAndNodIds(array $categoryNodes): array
    {
        $categories = [];

        foreach ($categoryNodes as $categoryNode) {
            $categories[$categoryNode[static::COLUMN_FK_LOCALE]][$categoryNode[static::COLUMN_FK_CATEGORY_NODE_DESCENDANT]][] = $categoryNode;
        }

        return $categories;
    }
}
