<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class ConfigurableBundleTemplateEditTabs extends AbstractConfigurableBundleTemplateTabs
{
    protected const SLOTS_TAB_NAME = 'Slots';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabsViewTransfer = parent::build($tabsViewTransfer);

        $this->addSlotsTab($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addSlotsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName(strtolower(static::SLOTS_TAB_NAME))
            ->setTitle(static::SLOTS_TAB_NAME)
            ->setTemplate('@ConfigurableBundleGui/Template/tabs/slots-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
