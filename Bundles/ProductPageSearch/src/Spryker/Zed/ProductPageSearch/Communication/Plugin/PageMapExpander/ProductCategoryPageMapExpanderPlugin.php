<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedCategoryQueryExpanderPlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearch\Dependency\Plugin\ProductPageMapExpanderInterface;
use Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface;

/**
 * @deprecated Use `\Spryker\Zed\ProductPageSearch\Communication\Plugin\ProductPageSearch\Elasticsearch\ProductCategoryPageMapExpanderPlugin` instead.
 *
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductCategoryPageMapExpanderPlugin extends AbstractPlugin implements ProductPageMapExpanderInterface
{
    public const RESULT_FIELD_PRODUCT_ORDER = 'product_order';

    protected const KEY_SORTED_CATEGORIES = 'sorted_categories';
    protected const KEY_ALL_NODE_PARENTS = 'all_node_parents';
    protected const KEY_PRODUCT_ORDER = 'product_order';

    /**
     * @var array
     */
    protected static $categoryTree;

    /**
     * @var string
     */
    protected static $categoryName;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductPageMap(PageMapTransfer $pageMapTransfer, PageMapBuilderInterface $pageMapBuilder, array $productData, LocaleTransfer $localeTransfer)
    {
        $directParentCategories = $productData['category_node_ids'];
        $allParentCategories = $productData['all_parent_category_ids'];

        $pageMapBuilder->addCategory($pageMapTransfer, $allParentCategories, $directParentCategories);

        $this->setFullTextSearch(
            $pageMapBuilder,
            $pageMapTransfer,
            $allParentCategories,
            $directParentCategories,
            $productData
        );

        $this->setSorting(
            $pageMapBuilder,
            $pageMapTransfer,
            $productData
        );

        return $pageMapTransfer;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $allParentCategories
     * @param array $directParentCategories
     * @param array $productData
     *
     * @return void
     */
    protected function setFullTextSearch(
        PageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        array $allParentCategories,
        array $directParentCategories,
        array $productData
    ): void {
        $boostedCategoryNames = $productData['boosted_category_names'];
        foreach ($directParentCategories as $idCategory) {
            if (isset($boostedCategoryNames[$idCategory])) {
                $pageMapBuilder->addFullTextBoosted($pageMapTransfer, $boostedCategoryNames[$idCategory]);
            }
        }

        $categoryNames = $productData['category_names'];
        foreach ($allParentCategories as $idCategory) {
            if (in_array($idCategory, $directParentCategories)) {
                continue;
            }

            if (isset($categoryNames[$idCategory])) {
                $pageMapBuilder->addFullText($pageMapTransfer, $categoryNames[$idCategory]);
            }
        }
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $productData
     *
     * @return void
     */
    protected function setSorting(
        PageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        array $productData
    ): void {
        $sortedCategories = $productData[static::KEY_SORTED_CATEGORIES];
        $parentCategoryTreesToUpdateSorting = $this->getParentCategoryTreesToUpdateSorting($sortedCategories);

        foreach ($sortedCategories as $idCategoryNode => $sortedCategory) {
            $pageMapBuilder->addIntegerSort(
                $pageMapTransfer,
                SortedCategoryQueryExpanderPlugin::buildSortFieldName($idCategoryNode),
                $sortedCategory[static::KEY_PRODUCT_ORDER]
            );

            $this->setSortingForTreeParents(
                $pageMapBuilder,
                $pageMapTransfer,
                $sortedCategory[static::KEY_PRODUCT_ORDER],
                $parentCategoryTreesToUpdateSorting[$idCategoryNode]
            );
        }
    }

    /**
     * @param array[] $sortedCategories
     *
     * @return int[][]
     */
    protected function getParentCategoryTreesToUpdateSorting(array $sortedCategories): array
    {
        $parentCategoryTreesToUpdateSorting = [];

        foreach ($sortedCategories as $idCategoryNode => $sortedCategory) {
            $parentCategoryTreesToUpdateSorting[$idCategoryNode] = $this->getSanitizedParentCategoryTree($sortedCategories, $idCategoryNode);
        }

        return $parentCategoryTreesToUpdateSorting;
    }

    /**
     * @param array $sortedCategories
     * @param int $idCurrentCategoryNode
     *
     * @return int[]
     */
    protected function getSanitizedParentCategoryTree(array $sortedCategories, int $idCurrentCategoryNode): array
    {
        if (!$this->hasCategoryParentNodes($sortedCategories, $idCurrentCategoryNode)) {
            return [];
        }

        $currentCategoryParentNodeIds = $sortedCategories[$idCurrentCategoryNode][static::KEY_ALL_NODE_PARENTS];

        foreach ($sortedCategories as $idCategoryNode => $sortedCategory) {
            if (!$this->canCategoryBeProcessed($sortedCategories, $idCurrentCategoryNode, $idCategoryNode)) {
                continue;
            }

            $categoryParentNodeIds = $sortedCategories[$idCategoryNode][static::KEY_ALL_NODE_PARENTS];

            if (!in_array($idCategoryNode, $currentCategoryParentNodeIds, true)) {
                continue;
            }

            $currentCategoryParentNodeIds = array_diff($currentCategoryParentNodeIds, $categoryParentNodeIds);
        }

        return $currentCategoryParentNodeIds;
    }

    /**
     * @param array $sortedCategories
     * @param int $idCategoryNode
     *
     * @return bool
     */
    protected function hasCategoryParentNodes(array $sortedCategories, int $idCategoryNode): bool
    {
        return isset($sortedCategories[$idCategoryNode][static::KEY_ALL_NODE_PARENTS]);
    }

    /**
     * @param array $sortedCategories
     * @param int $idCurrentCategoryNode
     * @param int $idCategoryNode
     *
     * @return bool
     */
    protected function canCategoryBeProcessed(array $sortedCategories, int $idCurrentCategoryNode, int $idCategoryNode): bool
    {
        if ($idCurrentCategoryNode === $idCategoryNode
            || !$this->hasCategoryParentNodes($sortedCategories, $idCategoryNode)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param int $productOrder
     * @param int[] $parentCategoryNodeIds
     *
     * @return void
     */
    protected function setSortingForTreeParents(
        PageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        $productOrder,
        array $parentCategoryNodeIds
    ): void {
        foreach ($parentCategoryNodeIds as $idParentCategoryNode) {
            $pageMapBuilder->addIntegerSort(
                $pageMapTransfer,
                SortedCategoryQueryExpanderPlugin::buildSortFieldName($idParentCategoryNode),
                $productOrder
            );
        }
    }
}
