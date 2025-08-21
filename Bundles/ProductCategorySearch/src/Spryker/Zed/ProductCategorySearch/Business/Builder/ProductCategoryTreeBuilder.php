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
    /**
     * @var string
     */
    protected const COLUMN_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @var string
     */
    protected const COLUMN_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';

    /**
     * @var string
     */
    protected const COLUMN_FK_LOCALE = 'fk_locale';

    /**
     * @var string
     */
    protected const COLUMN_CATEGORY_NAME = 'category_name';

    /**
     * @var string
     */
    protected const COLUMN_STORE_NAME = 'store_name';

    /**
     * @var array<array<array<array<int>>>>
     */
    protected static $categoryTreeIds = [];

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
     * @param bool $usesCache
     *
     * @return array<array<int>>
     */
    public function buildProductCategoryTree(LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer, bool $usesCache = false): array
    {
        $storeName = $storeTransfer->getNameOrFail();
        $idLocale = $localeTransfer->getIdLocaleOrFail();

        if ($usesCache === true && isset(static::$categoryTreeIds[$storeName][$idLocale])) {
            return static::$categoryTreeIds[$storeName][$idLocale];
        }

        $categoryTree = [];

        $categoryNodeIds = $this->productCategorySearchRepository->getCategoryNodeIdsByLocaleAndStore($localeTransfer, $storeTransfer);
        $categoryNodes = $this->productCategorySearchRepository->getAllCategoriesWithAttributesAndOrderByDescendant();
        $formattedCategoriesByLocaleAndStoreAndNodeIds = $this->formatCategoriesByNodeIdsForLocaleAndStore($categoryNodes);

        foreach ($categoryNodeIds as $idCategoryNode) {
            $categoryTree = $this->buildProductCategoryTreeByIdCategoryNodeForStoreAndLocale(
                $categoryTree,
                $idCategoryNode,
                $formattedCategoriesByLocaleAndStoreAndNodeIds[$storeTransfer->getName()][$localeTransfer->getIdLocale()][$idCategoryNode] ?? [],
            );
        }

        static::$categoryTreeIds[$storeName][$idLocale] = $categoryTree;

        return static::$categoryTreeIds[$storeName][$idLocale];
    }

    /**
     * @param array<int> $categoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array<array<string>>
     */
    public function buildProductCategoryTreeNames(array $categoryNodeIds, LocaleTransfer $localeTransfer): array
    {
        $categoryNames = [];
        $categoryAttributes = $this->productCategorySearchRepository
            ->getCategoryAttributesByLocale($categoryNodeIds, $localeTransfer);

        $categoryNames[$localeTransfer->getIdLocale()] = [];

        foreach ($categoryAttributes as $categoryAttribute) {
            $idCategoryNode = (int)$categoryAttribute[static::COLUMN_ID_CATEGORY_NODE];
            $categoryName = $categoryAttribute[static::COLUMN_CATEGORY_NAME];

            $categoryNames[$localeTransfer->getIdLocale()][$idCategoryNode] = $categoryName;
        }

        return $categoryNames;
    }

    /**
     * @param array<array<int>> $categoryTree
     * @param int $idCategoryNode
     * @param array $categories
     *
     * @return array<array<int>>
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
