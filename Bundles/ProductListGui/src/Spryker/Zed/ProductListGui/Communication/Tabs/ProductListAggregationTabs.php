<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ProductListAggregationTabs extends AbstractTabs
{
    public const GENERAL_TAB_NAME = 'general';
    public const GENERAL_TAB_TITLE = 'General Information';
    public const GENERAL_TAB_TEMPLATE = '@ProductListGui/_partials/_tabs/general-information.twig';

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
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addGeneralInformationTab($tabsViewTransfer)
            ->addProductListCategoryRelationTab($tabsViewTransfer)
            ->addProductListProductConcreteRelationTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralInformationTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::GENERAL_TAB_NAME)
            ->setTitle(static::GENERAL_TAB_TITLE)
            ->setTemplate(static::GENERAL_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductListCategoryRelationTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
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
    protected function addProductListProductConcreteRelationTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::PRODUCTS_TAB_NAME)
            ->setTitle(static::PRODUCTS_TAB_TITLE)
            ->setTemplate(static::PRODUCTS_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer)
    {
        $tabsViewTransfer->setFooterTemplate('@ProductListGui/_partials/_tabs/submit-button.twig');

        return $this;
    }
}
