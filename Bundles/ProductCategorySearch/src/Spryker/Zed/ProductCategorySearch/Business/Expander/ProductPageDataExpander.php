<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business\Expander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\ProductCategorySearch\Business\Builder\ProductCategoryTreeBuilderInterface;

class ProductPageDataExpander implements ProductPageDataExpanderInterface
{
    /**
     * @uses \Spryker\Shared\ProductPageSearch\ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA
     * @var string
     */
    protected const PRODUCT_ABSTRACT_PAGE_LOAD_DATA = 'PRODUCT_ABSTRACT_PAGE_LOAD_DATA';

    /**
     * @var string
     */
    protected const COLUMN_ID_CATEGORY_NODE = 'id_category_node';

    /**
     * @var string
     */
    protected const COLUMN_PRODUCT_ORDER = 'product_order';

    /**
     * @var string
     */
    protected const COLUMN_ALL_NODE_PARENTS = 'all_node_parents';

    /**
     * @var string
     */
    protected const RELATION_LOCALE = 'Locale';

    /**
     * @var string
     */
    protected const ID_LOCALE = 'id_locale';

    /**
     * @var array<int[][][]>
     */
    protected static $categoryTreeIds;

    /**
     * @var \Spryker\Zed\ProductCategorySearch\Business\Builder\ProductCategoryTreeBuilderInterface
     */
    protected $productCategoryTreeBuilder;

    /**
     * @param \Spryker\Zed\ProductCategorySearch\Business\Builder\ProductCategoryTreeBuilderInterface $productCategoryTreeBuilder
     */
    public function __construct(ProductCategoryTreeBuilderInterface $productCategoryTreeBuilder)
    {
        $this->productCategoryTreeBuilder = $productCategoryTreeBuilder;
    }

    /**
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageDataWithCategoryData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void
    {
        $productCategoryEntities = $productData[static::PRODUCT_ABSTRACT_PAGE_LOAD_DATA]->getCategories();
        $categoryNodeIds = $this->extractCategoryNodeIdsFromProductCategoryEntities($productCategoryEntities[$productAbstractPageSearchTransfer->getStore()] ?? []);
        $productAbstractPageSearchTransfer->setCategoryNodeIds($categoryNodeIds);
        if ($categoryNodeIds === []) {
            return;
        }

        $localeTransfer = (new LocaleTransfer())->setIdLocale($productData[static::RELATION_LOCALE][static::ID_LOCALE]);
        $storeTransfer = (new StoreTransfer())->setName($productAbstractPageSearchTransfer->getStoreOrFail());

        $allParentCategoryNodeIds = $this->getAllParentCategoryNodeIds($productAbstractPageSearchTransfer, $localeTransfer, $storeTransfer);
        $productAbstractPageSearchTransfer->setAllParentCategoryIds($allParentCategoryNodeIds);

        $this->setNames(
            $allParentCategoryNodeIds,
            $productAbstractPageSearchTransfer->getCategoryNodeIds(),
            $localeTransfer,
            $productAbstractPageSearchTransfer
        );

        $this->setSorting(
            $allParentCategoryNodeIds,
            $localeTransfer,
            $storeTransfer,
            $productAbstractPageSearchTransfer,
            $productCategoryEntities
        );
    }

    /**
     * @param array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory> $productCategoryEntities
     *
     * @return array<int>
     */
    protected function extractCategoryNodeIdsFromProductCategoryEntities(array $productCategoryEntities): array
    {
        if ($productCategoryEntities === []) {
            return [];
        }

        $categoryNodeIds = [];
        foreach ($productCategoryEntities as $productCategoryEntity) {
            $idCategoryNode = $productCategoryEntity->getVirtualColumn(static::COLUMN_ID_CATEGORY_NODE);
            if ($idCategoryNode && !in_array($idCategoryNode, $categoryNodeIds, true)) {
                $categoryNodeIds[] = $idCategoryNode;
            }
        }

        return $categoryNodeIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<int>
     */
    protected function getAllParentCategoryNodeIds(
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        LocaleTransfer $localeTransfer,
        StoreTransfer $storeTransfer
    ): array {
        $allParentCategoryNodeIds = [];

        foreach ($productAbstractPageSearchTransfer->getCategoryNodeIds() as $idCategoryNode) {
            $allParentCategoryNodeIds[] = $this->getCategoryNodeParentIds($idCategoryNode, $localeTransfer, $storeTransfer);
        }

        $allParentCategoryNodeIds = array_merge(...$allParentCategoryNodeIds);
        $allParentCategoryNodeIds = array_values(array_unique($allParentCategoryNodeIds));

        return $allParentCategoryNodeIds;
    }

    /**
     * @param array<int> $allParentCategoryNodeIds
     * @param array<int> $directParentCategoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    protected function setNames(
        array $allParentCategoryNodeIds,
        array $directParentCategoryNodeIds,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer
    ): void {
        $categoryNodeIds = array_unique(array_merge($allParentCategoryNodeIds, $directParentCategoryNodeIds));
        $categoryTreeNames = $this->productCategoryTreeBuilder
            ->buildProductCategoryTreeNames($categoryNodeIds, $localeTransfer);

        $this->setBoostedCategoryNames(
            $directParentCategoryNodeIds,
            $localeTransfer,
            $productAbstractPageSearchTransfer,
            $categoryTreeNames
        );
        $this->setCategoryNames(
            $allParentCategoryNodeIds,
            $directParentCategoryNodeIds,
            $localeTransfer,
            $productAbstractPageSearchTransfer,
            $categoryTreeNames
        );
    }

    /**
     * @param array<int> $allParentCategoryNodeIds
     * @param array<int> $directParentCategoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param array<string[]> $categoryTreeNames
     *
     * @return void
     */
    protected function setCategoryNames(
        array $allParentCategoryNodeIds,
        array $directParentCategoryNodeIds,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        array $categoryTreeNames
    ): void {
        $categoryNames = [];

        foreach ($allParentCategoryNodeIds as $idCategoryNode) {
            if (in_array($idCategoryNode, $directParentCategoryNodeIds)) {
                continue;
            }

            $categoryName = $categoryTreeNames[$localeTransfer->getIdLocale()][$idCategoryNode] ?? null;

            if ($categoryName !== null) {
                $categoryNames[$idCategoryNode] = $categoryName;
            }
        }

        $productAbstractPageSearchTransfer->setCategoryNames($categoryNames);
    }

    /**
     * @param array<int> $directParentCategoryNodeIds
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param array<string[]> $categoryTreeNames
     *
     * @return void
     */
    protected function setBoostedCategoryNames(
        array $directParentCategoryNodeIds,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        array $categoryTreeNames
    ): void {
        $boostedCategoryNames = [];

        foreach ($directParentCategoryNodeIds as $idCategoryNode) {
            $boostedName = $categoryTreeNames[$localeTransfer->getIdLocale()][$idCategoryNode] ?? null;

            if ($boostedName !== null) {
                $boostedCategoryNames[$idCategoryNode] = $boostedName;
            }
        }

        $productAbstractPageSearchTransfer->setBoostedCategoryNames($boostedCategoryNames);
    }

    /**
     * @param array<int> $directParentCategories
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory[]> $productCategoryEntities
     *
     * @return void
     */
    protected function setSorting(
        array $directParentCategories,
        LocaleTransfer $localeTransfer,
        StoreTransfer $storeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        array $productCategoryEntities
    ): void {
        $storeName = $storeTransfer->getName();
        $filteredProductCategoriesByDirectParents = [];

        if (!$productCategoryEntities || !isset($productCategoryEntities[$storeName])) {
            $productAbstractPageSearchTransfer->setSortedCategories([]);

            return;
        }

        foreach ($productCategoryEntities[$storeName] as $productCategoryEntity) {
            if (in_array($productCategoryEntity->getVirtualColumn(static::COLUMN_ID_CATEGORY_NODE), $directParentCategories)) {
                $filteredProductCategoriesByDirectParents[] = $productCategoryEntity;
            }
        }

        $sortedCategories = $this->sortCategories($filteredProductCategoriesByDirectParents, $localeTransfer, $storeTransfer);
        $productAbstractPageSearchTransfer->setSortedCategories($sortedCategories);
    }

    /**
     * @param array<\Orm\Zed\ProductCategory\Persistence\SpyProductCategory> $productCategoryEntities
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function sortCategories(array $productCategoryEntities, LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer): array
    {
        $maxProductOrder = (pow(2, 31) - 1);
        $sortedCategories = [];

        foreach ($productCategoryEntities as $productCategoryEntity) {
            $idCategoryNode = $productCategoryEntity->getVirtualColumn(static::COLUMN_ID_CATEGORY_NODE);

            $productOrder = (int)$productCategoryEntity->getProductOrder() ?: $maxProductOrder;
            $sortedCategories[$idCategoryNode][static::COLUMN_PRODUCT_ORDER] = $productOrder;
            $allNodeParents = $this->getCategoryNodeParentIds($idCategoryNode, $localeTransfer, $storeTransfer);
            $sortedCategories[$idCategoryNode][static::COLUMN_ALL_NODE_PARENTS] = $allNodeParents;
        }

        return $sortedCategories;
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array<int>
     */
    protected function getCategoryNodeParentIds(int $idCategoryNode, LocaleTransfer $localeTransfer, StoreTransfer $storeTransfer): array
    {
        $storeName = $storeTransfer->getNameOrFail();
        $idLocale = $localeTransfer->getIdLocaleOrFail();

        if (!isset(static::$categoryTreeIds[$storeName][$idLocale])) {
            static::$categoryTreeIds[$storeName][$idLocale] = $this->productCategoryTreeBuilder->buildProductCategoryTree(
                $localeTransfer,
                $storeTransfer
            );
        }

        return static::$categoryTreeIds[$storeName][$idLocale][$idCategoryNode];
    }
}
