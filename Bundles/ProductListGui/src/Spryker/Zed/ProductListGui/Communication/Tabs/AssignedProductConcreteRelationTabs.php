<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AssignedProductConcreteRelationTabs extends AbstractTabs
{
    public const ASSIGNED_PRODUCT_TAB_NAME = 'assigned_product';
    public const ASSIGNED_PRODUCT_TAB_TITLE = 'Products in this list';
    public const ASSIGNED_PRODUCT_TAB_TEMPLATE = '@ProductListGui/_partials/_tables/assigned-product-table.twig';

    public const DEASSIGNED_PRODUCT_TAB_NAME = 'deassignment_product';
    public const DEASSIGNED_PRODUCT_TAB_TITLE = 'Products to be deassigned';
    public const DEASSIGNED_PRODUCT_TAB_TEMPLATE = '@ProductListGui/_partials/_tables/deassignment-product-table.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this->addAssignedProductTab($tabsViewTransfer)
            ->addDeassignmentProductTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssignedProductTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::ASSIGNED_PRODUCT_TAB_NAME)
            ->setTitle(static::ASSIGNED_PRODUCT_TAB_TITLE)
            ->setTemplate(static::ASSIGNED_PRODUCT_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addDeassignmentProductTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::DEASSIGNED_PRODUCT_TAB_NAME)
            ->setTitle(static::DEASSIGNED_PRODUCT_TAB_TITLE)
            ->setTemplate(static::DEASSIGNED_PRODUCT_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
