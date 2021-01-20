<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategorySearch\Business\Expander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\ProductCategorySearch\Persistence\ProductCategorySearchRepositoryInterface;

class ProductPageDataExpander implements ProductPageDataExpanderInterface
{
    /**
     * @uses \Spryker\Shared\ProductPageSearch\ProductPageSearchConfig::PRODUCT_ABSTRACT_PAGE_LOAD_DATA
     */
    protected const PRODUCT_ABSTRACT_PAGE_LOAD_DATA = 'PRODUCT_ABSTRACT_PAGE_LOAD_DATA';

    protected const COLUMN_ID_CATEGORY_NODE = 'id_category_node';
    protected const COLUMN_FK_CATEGORY_NODE_DESCENDANT = 'fk_category_node_descendant';
    protected const COLUMN_FK_LOCALE = 'fk_locale';

    /**
     * @var array
     */
    protected static $categoryTree;

    /**
     * @var array
     */
    protected static $categoryNames = [];

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
     * @param array $productData
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    public function expandProductPageData(array $productData, ProductPageSearchTransfer $productAbstractPageSearchTransfer): void
    {
        $allParentCategoryIds = [];
        $productCategoryEntities = $productData[static::PRODUCT_ABSTRACT_PAGE_LOAD_DATA]->getCategories();
        $localeTransfer = (new LocaleTransfer())->setIdLocale($productData['Locale']['id_locale']);

        foreach ($productAbstractPageSearchTransfer->getCategoryNodeIds() as $idCategory) {
            $allParentCategoryIds[] = $this->getAllParents($idCategory, $localeTransfer);
        }

        $allParentCategoryIds = array_merge(...$allParentCategoryIds);
        $allParentCategoryIds = array_values(array_unique($allParentCategoryIds));

        $productAbstractPageSearchTransfer->setAllParentCategoryIds($allParentCategoryIds);

        $this->setCategoryNames(
            $allParentCategoryIds,
            $productAbstractPageSearchTransfer->getCategoryNodeIds(),
            $localeTransfer,
            $productAbstractPageSearchTransfer
        );

        $this->setSorting(
            $allParentCategoryIds,
            $localeTransfer,
            $productAbstractPageSearchTransfer,
            $productCategoryEntities
        );
    }

    /**
     * @param int $idCategoryNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function getAllParents(int $idCategoryNode, LocaleTransfer $localeTransfer): array
    {
        if (static::$categoryTree === null) {
            $this->loadTree($localeTransfer);
        }

        return static::$categoryTree[$idCategoryNode];
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function loadTree(LocaleTransfer $localeTransfer): void
    {
        static::$categoryTree = [];

        $categoryNodeEntities = $this->productCategorySearchRepository->getCategoryNodesByLocale($localeTransfer);
        $categoryEntities = $this->productCategorySearchRepository->getAllCategoriesWithAttributesAndOrderByDescendant();
        $formattedCategoriesByLocaleAndNodeIds = $this->formatCategoriesWithLocaleAndNodIds($categoryEntities);

        foreach ($categoryNodeEntities as $categoryNodeEntity) {
            $pathData = [];

            if (isset($formattedCategoriesByLocaleAndNodeIds[$localeTransfer->getIdLocale()][$categoryNodeEntity->getIdCategoryNode()])) {
                $pathData = $formattedCategoriesByLocaleAndNodeIds[$localeTransfer->getIdLocale()][$categoryNodeEntity->getIdCategoryNode()];
            }

            static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()] = [];

            foreach ($pathData as $path) {
                $idCategory = (int)$path[static::COLUMN_ID_CATEGORY_NODE];
                if (!in_array($idCategory, static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()])) {
                    static::$categoryTree[$categoryNodeEntity->getIdCategoryNode()][] = $idCategory;
                }
            }
        }
    }

    /**
     * @param array $allParentCategories
     * @param array $directParentCategories
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     *
     * @return void
     */
    protected function setCategoryNames(
        array $allParentCategories,
        array $directParentCategories,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer
    ): void {
        $boostedCategoryNames = [];

        foreach ($directParentCategories as $idCategory) {
            $boostedName = $this->getName($idCategory, $localeTransfer);

            if ($boostedName !== null) {
                $boostedCategoryNames[$idCategory] = $boostedName;
            }
        }

        $categoryNames = [];
        $productAbstractPageSearchTransfer->setBoostedCategoryNames($boostedCategoryNames);

        foreach ($allParentCategories as $idCategory) {
            if (in_array($idCategory, $directParentCategories)) {
                continue;
            }

            $categoryName = $this->getName($idCategory, $localeTransfer);

            if ($categoryName !== null) {
                $categoryNames[$idCategory] = $categoryName;
            }
        }

        $productAbstractPageSearchTransfer->setCategoryNames($categoryNames);
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function getName(int $idCategory, LocaleTransfer $localeTransfer): ?string
    {
        $idLocale = $localeTransfer->getIdLocale();

        if (!isset(static::$categoryNames[$idLocale])) {
            $this->loadNames($localeTransfer);
        }

        return static::$categoryNames[$idLocale][$idCategory] ?? null;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    protected function loadNames(LocaleTransfer $localeTransfer): void
    {
        $categoryAttributeEntities = $this->productCategorySearchRepository
            ->getCategoryAttributesByLocale($localeTransfer);

        static::$categoryNames[$localeTransfer->getIdLocale()] = [];

        foreach ($categoryAttributeEntities as $categoryAttributeEntity) {
            static::$categoryNames[$localeTransfer->getIdLocale()][$categoryAttributeEntity->getFkCategory()] = $categoryAttributeEntity->getName();
        }
    }

    /**
     * @param array $directParentCategories
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productAbstractPageSearchTransfer
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[][] $productCategoryEntities
     *
     * @return void
     */
    protected function setSorting(
        array $directParentCategories,
        LocaleTransfer $localeTransfer,
        ProductPageSearchTransfer $productAbstractPageSearchTransfer,
        array $productCategoryEntities
    ): void {
        $store = $productAbstractPageSearchTransfer->getStore();
        $filteredProductCategoriesByDirectParents = [];

        if ($productCategoryEntities && isset($productCategoryEntities[$store])) {
            foreach ($productCategoryEntities[$store] as $productCategoryEntity) {
                if (in_array($productCategoryEntity->getVirtualColumn(static::COLUMN_ID_CATEGORY_NODE), $directParentCategories)) {
                    $filteredProductCategoriesByDirectParents[] = $productCategoryEntity;
                }
            }
        }

        $sortedCategories = $this->sortCategories($filteredProductCategoriesByDirectParents, $localeTransfer);
        $productAbstractPageSearchTransfer->setSortedCategories($sortedCategories);
    }

    /**
     * @param \Orm\Zed\ProductCategory\Persistence\SpyProductCategory[] $productCategoryEntities
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    protected function sortCategories(array $productCategoryEntities, LocaleTransfer $localeTransfer): array
    {
        $maxProductOrder = (pow(2, 31) - 1);
        $sortedCategories = [];

        foreach ($productCategoryEntities as $productCategoryEntity) {
            $idCategoryNode = $productCategoryEntity->getVirtualColumn(static::COLUMN_ID_CATEGORY_NODE);

            $productOrder = (int)$productCategoryEntity->getProductOrder() ?: $maxProductOrder;
            $sortedCategories[$idCategoryNode]['product_order'] = $productOrder;
            $allNodeParents = $this->getAllParents($idCategoryNode, $localeTransfer);
            $sortedCategories[$idCategoryNode]['all_node_parents'] = $allNodeParents;
        }

        return $sortedCategories;
    }

    /**
     * @param array $categoryEntities
     *
     * @return array
     */
    protected function formatCategoriesWithLocaleAndNodIds(array $categoryEntities): array
    {
        $categories = [];

        foreach ($categoryEntities as $categoryEntity) {
            $categories[$categoryEntity[static::COLUMN_FK_LOCALE]][$categoryEntity[static::COLUMN_FK_CATEGORY_NODE_DESCENDANT]][] = $categoryEntity;
        }

        return $categories;
    }
}
