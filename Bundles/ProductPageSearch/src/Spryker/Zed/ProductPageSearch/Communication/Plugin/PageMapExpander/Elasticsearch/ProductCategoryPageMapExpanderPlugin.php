<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\PageMapExpander\Elasticsearch;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Spryker\Client\Search\Plugin\Elasticsearch\QueryExpander\SortedCategoryQueryExpanderPlugin;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractPageMapExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductCategoryPageMapExpanderPlugin extends AbstractPlugin implements ProductAbstractPageMapExpanderPluginInterface
{
    protected const KEY_CATEGORY_NODE_IDS = 'category_node_ids';
    protected const KEY_ALL_PARENT_CATEGORY_NODE_IDS = 'all_parent_category_ids';
    protected const KEY_SORTED_CATEGORIES = 'sorted_categories';
    protected const KEY_ALL_NODE_PARENTS = 'all_node_parents';
    protected const KEY_PRODUCT_ORDER = 'product_order';
    protected const KEY_CATEGORY_NAMES = 'category_names';
    protected const KEY_BOOSTED_CATEGORY_NAMES = 'boosted_category_names';

    /**
     * @var array
     */
    protected static $categoryTree;

    /**
     * @var string
     */
    protected static $categoryName;

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductPageMap(PageMapTransfer $pageMapTransfer, PageMapBuilderInterface $pageMapBuilder, array $productData, LocaleTransfer $localeTransfer)
    {
        $directParentCategories = $productData[static::KEY_CATEGORY_NODE_IDS];
        $allParentCategories = $productData[static::KEY_ALL_PARENT_CATEGORY_NODE_IDS];

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
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
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
    ) {
        $boostedCategoryNames = $productData[static::KEY_BOOSTED_CATEGORY_NAMES];
        foreach ($directParentCategories as $idCategory) {
            if (isset($boostedCategoryNames[$idCategory])) {
                $pageMapBuilder->addFullTextBoosted($pageMapTransfer, $boostedCategoryNames[$idCategory]);
            }
        }

        $categoryNames = $productData[static::KEY_CATEGORY_NAMES];
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
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param array $productData
     *
     * @return void
     */
    protected function setSorting(
        PageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        array $productData
    ) {
        $sortedCategories = $productData[static::KEY_SORTED_CATEGORIES];
        foreach ($sortedCategories as $idCategoryNode => $sortedCategory) {
            $pageMapBuilder->addIntegerSort(
                $pageMapTransfer,
                SortedCategoryQueryExpanderPlugin::buildSortFieldName($idCategoryNode),
                $sortedCategory[static::KEY_PRODUCT_ORDER]
            );

            $this->setSortingForTreeParents(
                $pageMapBuilder,
                $pageMapTransfer,
                $idCategoryNode,
                $sortedCategory[static::KEY_PRODUCT_ORDER],
                $productData
            );
        }
    }

    /**
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param int $idCategoryNode
     * @param int $productOrder
     * @param array $productData
     *
     * @return void
     */
    protected function setSortingForTreeParents(
        PageMapBuilderInterface $pageMapBuilder,
        PageMapTransfer $pageMapTransfer,
        $idCategoryNode,
        $productOrder,
        array $productData
    ) {
        if (!isset($productData[static::KEY_SORTED_CATEGORIES][$idCategoryNode][static::KEY_ALL_NODE_PARENTS])) {
            return;
        }

        $idsParentCategoryNode = $productData[static::KEY_SORTED_CATEGORIES][$idCategoryNode][static::KEY_ALL_NODE_PARENTS];
        foreach ($idsParentCategoryNode as $idParentCategoryNode) {
            $pageMapBuilder->addIntegerSort(
                $pageMapTransfer,
                SortedCategoryQueryExpanderPlugin::buildSortFieldName($idParentCategoryNode),
                $productOrder
            );
        }
    }
}
