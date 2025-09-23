<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class ModelAttachmentTabs extends AbstractTabs
{
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this
            ->addAttachModelTab($tabsViewTransfer)
            ->addModelsToBeAttachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachModelTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('attach-model')
            ->setTitle('Attach model')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/model/attach-model-content.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    protected function addModelsToBeAttachedTab(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('models-to-be-attached')
            ->setTitle('Models to be attached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/model/models-to-be-attached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
