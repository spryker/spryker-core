<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ModelAssetRelationTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addUnattachedAssetsTab($tabsViewTransfer)
            ->addAssetsToBeAttachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addUnattachedAssetsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('unattached-assets')
            ->setTitle('Available Assets')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/asset/unattached-assets-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssetsToBeAttachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('assets-to-be-attached')
            ->setTitle('Assets to be Attached')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/asset/assets-to-be-attached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
