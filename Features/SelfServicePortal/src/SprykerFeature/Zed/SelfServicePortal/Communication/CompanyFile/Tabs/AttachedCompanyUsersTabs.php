<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AttachedCompanyUsersTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const TAB_ATTACHED_COMPANY_USERS = 'attached-company-users';

    /**
     * @var string
     */
    protected const TAB_COMPANY_USERS_TO_BE_UNATTACHED = 'company-users-to-be-unattached';

    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAttachedCompanyUsersTab($tabsViewTransfer)
            ->addCompanyUsersToBeUnattachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachedCompanyUsersTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_ATTACHED_COMPANY_USERS)
            ->setTitle('Attached Company Users')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company-users/attached-company-users-table.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addCompanyUsersToBeUnattachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_COMPANY_USERS_TO_BE_UNATTACHED)
            ->setTitle('Company Users to be detached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company-users/company-users-to-be-unattached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
