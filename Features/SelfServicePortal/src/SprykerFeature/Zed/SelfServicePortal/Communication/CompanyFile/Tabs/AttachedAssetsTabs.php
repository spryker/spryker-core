<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AttachedAssetsTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAssignedTab($tabsViewTransfer)
            ->addToBeDetachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    protected function addAssignedTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('attached-assets')
            ->setTitle('Attached assets')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/asset/attached-assets-table.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    protected function addToBeDetachedTab(TabsViewTransfer $tabsViewTransfer): self
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('assets-to-be-detached')
            ->setTitle('Assets to be detached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/asset/assets-to-be-detached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
