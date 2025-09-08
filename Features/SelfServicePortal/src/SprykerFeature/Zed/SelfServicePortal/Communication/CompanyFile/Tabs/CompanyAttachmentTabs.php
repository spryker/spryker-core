<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class CompanyAttachmentTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const TAB_ATTACH_COMPANY = 'attach-company';

    /**
     * @var string
     */
    protected const TAB_COMPANIES_TO_BE_ATTACHED = 'companies-to-be-attached';

    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAttachCompanyTab($tabsViewTransfer)
            ->addCompaniesToBeAttachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachCompanyTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_ATTACH_COMPANY)
            ->setTitle('Attach Company')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company/attach-company-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addCompaniesToBeAttachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_COMPANIES_TO_BE_ATTACHED)
            ->setTitle('Companies to be attached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company/companies-to-be-attached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
