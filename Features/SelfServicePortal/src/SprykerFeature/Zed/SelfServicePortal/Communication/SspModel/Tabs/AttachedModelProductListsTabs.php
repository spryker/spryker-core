<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AttachedModelProductListsTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addAttachedProductListsTab($tabsViewTransfer)
            ->addProductListsToBeUnattachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachedProductListsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('attached-product-lists')
            ->setTitle('Assigned Product Lists')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/product-list/attached-product-lists-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addProductListsToBeUnattachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('product-lists-to-be-unattached')
            ->setTitle('Product Lists to be Detached')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/product-list/product-lists-to-be-unattached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
