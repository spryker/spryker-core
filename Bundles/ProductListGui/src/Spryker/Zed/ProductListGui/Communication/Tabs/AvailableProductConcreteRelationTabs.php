<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AvailableProductConcreteRelationTabs extends AbstractTabs
{
    public const AVAILABLE_TAB_NAME = 'available_product';
    public const AVAILABLE_TAB_TITLE = 'Select Products to assign';
    public const AVAILABLE_TAB_TEMPLATE = '@ProductListGui/_partials/_tables/available-product-table.twig';

    public const ASSIGNED_TAB_NAME = 'assignment_product';
    public const ASSIGNED_TAB_TITLE = 'Products to be assigned';
    public const ASSIGNED_TAB_TEMPLATE = '@ProductListGui/_partials/_tables/assignment-product-table.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this->addAvailableProductTab($tabsViewTransfer)
            ->addAssignmentProductTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAvailableProductTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::AVAILABLE_TAB_NAME)
            ->setTitle(static::AVAILABLE_TAB_TITLE)
            ->setTemplate(static::AVAILABLE_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssignmentProductTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::ASSIGNED_TAB_NAME)
            ->setTitle(static::ASSIGNED_TAB_TITLE)
            ->setTemplate(static::ASSIGNED_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
