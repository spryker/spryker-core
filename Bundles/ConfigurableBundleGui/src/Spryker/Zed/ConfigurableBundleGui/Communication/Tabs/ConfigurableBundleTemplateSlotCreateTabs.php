<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Tabs;

use Generated\Shared\Transfer\TabsViewTransfer;

class ConfigurableBundleTemplateSlotCreateTabs extends AbstractConfigurableBundleTabs
{
    protected const GENERAL_TAB_TEMPLATE = '@ConfigurableBundleGui/Slot/tabs/general-tab.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabsViewTransfer = parent::build($tabsViewTransfer);

        return $tabsViewTransfer->setIsNavigable(false);
    }
}
