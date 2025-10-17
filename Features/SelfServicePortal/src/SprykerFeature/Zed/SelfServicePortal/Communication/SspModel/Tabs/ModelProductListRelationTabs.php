<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ModelProductListRelationTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addUnattachedProductListsTab($tabsViewTransfer)
            ->addProductListsToBeAttachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addUnattachedProductListsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('unattached-product-lists')
            ->setTitle('Available Product Lists')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/product-list/unattached-product-lists-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductListsToBeAttachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('product-lists-to-be-attached')
            ->setTitle('Product Lists to be Attached')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/product-list/product-lists-to-be-attached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
