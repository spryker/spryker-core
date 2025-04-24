<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspAssetManagement\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class SspAssetTabs extends AbstractTabs
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addCompaniesTab($tabsViewTransfer);
        $this->addSspInquiriesTab($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addCompaniesTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('companies')
            ->setTitle('Companies')
            ->setTemplate('@SspAssetManagement/_partials/_tabs/tab-companies.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addSspInquiriesTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('ssp-inquiries')
            ->setTitle('Inquiries')
            ->setTemplate('@SspAssetManagement/_partials/_tabs/tab-ssp-inquiries.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
