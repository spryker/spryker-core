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
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 */
class ProductCategoryPageMapExpanderPlugin extends AbstractPlugin implements ProductPageMapExpanderInterface
{
    const RESULT_FIELD_PRODUCT_ORDER = 'product_order';

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
    ) {
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
    ) {
        $sortedCategories = $productData['sorted_categories'];
        foreach ($sortedCategories as $idCategoryNode => $sortedCategory) {
            $pageMapBuilder->addIntegerSort(
                $pageMapTransfer,
                SortedCategoryQueryExpanderPlugin::buildSortFieldName($idCategoryNode),
                $sortedCategory['product_order']
            );

            $this->setSortingForTreeParents(
                $pageMapBuilder,
                $pageMapTransfer,
                $idCategoryNode,
                $sortedCategory['product_order'],
                $productData
            );
        }
    }

    /**
     * @param \Spryker\Zed\Search\Business\Model\Elasticsearch\DataMapper\PageMapBuilderInterface $pageMapBuilder
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
        if (!isset($productData['sorted_categories'][$idCategoryNode]['all_node_parents'])) {
            return;
        }

        $idsParentCategoryNode = $productData['sorted_categories'][$idCategoryNode]['all_node_parents'];
        foreach ($idsParentCategoryNode as $idParentCategoryNode) {
            $pageMapBuilder->addIntegerSort(
                $pageMapTransfer,
                SortedCategoryQueryExpanderPlugin::buildSortFieldName($idParentCategoryNode),
                $productOrder
            );
        }
    }
}
