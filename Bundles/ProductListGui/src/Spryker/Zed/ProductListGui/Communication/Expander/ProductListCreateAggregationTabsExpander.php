<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class ProductListCreateAggregationTabsExpander implements ProductListCreateAggregationTabsExpanderInterface
{
    public const CATEGORIES_TAB_NAME = 'product_list_category_relation';
    public const CATEGORIES_TAB_TITLE = 'Assign Categories';
    public const CATEGORIES_TAB_TEMPLATE = '@ProductListGui/_partials/_tabs/product-list-category-relation.twig';

    public const PRODUCTS_TAB_NAME = 'product_list_product_concrete_relation';
    public const PRODUCTS_TAB_TITLE = 'Assign Products';
    public const PRODUCTS_TAB_TEMPLATE = '@ProductListGui/_partials/_tabs/product-list-product-concrete-relation.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expandWithProductListAssignmentTabs(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addProductListCategoryRelationTab($tabsViewTransfer)
            ->addProductListProductConcreteRelationTab($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductListCategoryRelationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName(static::CATEGORIES_TAB_NAME)
            ->setTitle(static::CATEGORIES_TAB_TITLE)
            ->setTemplate(static::CATEGORIES_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductListProductConcreteRelationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName(static::PRODUCTS_TAB_NAME)
            ->setTitle(static::PRODUCTS_TAB_TITLE)
            ->setTemplate(static::PRODUCTS_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
