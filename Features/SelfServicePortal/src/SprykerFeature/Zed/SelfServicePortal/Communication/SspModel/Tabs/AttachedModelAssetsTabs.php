<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\SspModel\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AttachedModelAssetsTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addAttachedSspAssetsTab($tabsViewTransfer)
            ->addAssetsToBeUnattachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachedSspAssetsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('attached-ssp-assets')
            ->setTitle('Assigned Assets')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/asset/attached-ssp-assets-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssetsToBeUnattachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('assets-to-be-unattached')
            ->setTitle('Assets to be Detached')
            ->setTemplate('@SelfServicePortal/AttachModel/_partials/asset/assets-to-be-unattached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
