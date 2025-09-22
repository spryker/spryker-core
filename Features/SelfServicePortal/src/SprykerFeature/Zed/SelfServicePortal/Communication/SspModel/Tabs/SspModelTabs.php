<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class SspModelTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabsViewTransfer->setIsNavigable(true);

        $this->addAttachedAssetsTab($tabsViewTransfer);
        $this->addAttachedProductListsTab($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    protected function addAttachedAssetsTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('attached-assets')
            ->setTitle('Attached Assets')
            ->setTemplate('@SelfServicePortal/_partials/_tabs/tab-attached-assets.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    protected function addAttachedProductListsTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('attached-product-lists')
            ->setTitle('Attached Product Lists')
            ->setTemplate('@SelfServicePortal/_partials/_tabs/tab-attached-product-lists.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
