<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\CompanyFile\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AttachedCompaniesTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const TAB_ATTACHED_COMPANIES = 'attached-companies';

    /**
     * @var string
     */
    protected const TAB_COMPANIES_TO_BE_UNATTACHED = 'companies-to-be-unattached';

    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAttachedCompaniesTab($tabsViewTransfer)
            ->addCompaniesToBeUnattachedTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAttachedCompaniesTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_ATTACHED_COMPANIES)
            ->setTitle('Attached Companies')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company/attached-companies-table.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addCompaniesToBeUnattachedTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::TAB_COMPANIES_TO_BE_UNATTACHED)
            ->setTitle('Companies to be detached')
            ->setTemplate('@SelfServicePortal/AttachFile/_partials/company/companies-to-be-unattached-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
