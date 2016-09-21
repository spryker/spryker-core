<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Tabs;

use Generated\Shared\Transfer\ZedTabsViewTransfer;

abstract class AbstractTabs implements TabsInterface
{

    /**
     * @var \Generated\Shared\Transfer\ZedTabsViewTransfer
     */
    protected $zedTabsViewTransfer;

    /**
     * @return \Generated\Shared\Transfer\ZedTabsViewTransfer
     */
    public function createView()
    {
        $zedTabsViewTransfer = $this->createZedTabsViewTransfer();
        $zedTabsViewTransfer = $this->build($zedTabsViewTransfer);
        $this->ensureActive($zedTabsViewTransfer);

        return $zedTabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ZedTabsViewTransfer $zedTabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\ZedTabsViewTransfer
     */
    abstract protected function build(ZedTabsViewTransfer $zedTabsViewTransfer);

    /**
     * @param \Generated\Shared\Transfer\ZedTabsViewTransfer $zedTabsViewTransfer
     *
     * @return void
     */
    private function ensureActive(ZedTabsViewTransfer $zedTabsViewTransfer)
    {
        if ($zedTabsViewTransfer->getActive() === null && $zedTabsViewTransfer->getTabs()->count()) {
            $firstTabName = $zedTabsViewTransfer->getTabs()[0]->getName();
            $zedTabsViewTransfer->setActive($firstTabName);
        }
    }

    /**
     * @return \Generated\Shared\Transfer\ZedTabsViewTransfer
     */
    protected function createZedTabsViewTransfer()
    {
        return new ZedTabsViewTransfer();
    }

}
