<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class CompanyUserAttachmentTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const TAB_ATTACH_COMPANY_USER = 'attach-company-user';

    /**
     * @var string
     */
    protected const TAB_COMPANY_USERS_TO_BE_ATTACHED = 'company-users-to-be-attached';

    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAttachCompanyUserTab($tabsViewTransfer)
            ->addCompanyUsersToBeAttachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachCompanyUserTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_ATTACH_COMPANY_USER)
            ->setTitle('Attach Company User')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company-users/attach-company-user-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addCompanyUsersToBeAttachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_COMPANY_USERS_TO_BE_ATTACHED)
            ->setTitle('Company Users to be attached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company-users/company-users-to-be-attached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
