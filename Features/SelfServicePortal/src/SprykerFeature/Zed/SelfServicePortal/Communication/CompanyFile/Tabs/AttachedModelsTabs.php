<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AttachedModelsTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addAttachedModelsTab($tabsViewTransfer)
            ->addModelsToBeDetachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachedModelsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('attached-models')
            ->setTitle('Attached Models')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/model/attached-models-content.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    protected function addModelsToBeDetachedTab(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('models-to-be-detached')
            ->setTitle('Models to be detached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/model/models-to-be-detached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
