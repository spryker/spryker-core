<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Tabs;

use Generated\Shared\Transfer\TabsViewTransfer;

abstract class AbstractTabs implements TabsInterface
{
    /**
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function createView()
    {
        $tabsViewTransfer = $this->createTabsViewTransfer();
        $tabsViewTransfer = $this->build($tabsViewTransfer);
        $this->ensureActive($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    abstract protected function build(TabsViewTransfer $tabsViewTransfer);

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return void
     */
    private function ensureActive(TabsViewTransfer $tabsViewTransfer)
    {
        if ($tabsViewTransfer->getActiveTabName() === null && $tabsViewTransfer->getTabs()->count()) {
            $firstTabName = $tabsViewTransfer->getTabs()[0]->getName();
            $tabsViewTransfer->setActiveTabName($firstTabName);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function createTabsViewTransfer()
    {
        return new TabsViewTransfer();
    }
}
