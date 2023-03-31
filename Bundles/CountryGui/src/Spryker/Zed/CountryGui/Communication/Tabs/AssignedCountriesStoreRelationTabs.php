<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AssignedCountriesStoreRelationTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const ASSIGNED_COUNTRY_TAB_NAME = 'assigned_country';

    /**
     * @var string
     */
    protected const ASSIGNED_COUNTRY_TAB_TITLE = 'Countries in this list';

    /**
     * @var string
     */
    protected const ASSIGNED_COUNTRY_TAB_TEMPLATE = '@CountryGui/_partials/_tables/assigned-country-table.twig';

    /**
     * @var string
     */
    protected const UNASSIGNED_COUNTRY_TAB_NAME = 'unassignment_country';

    /**
     * @var string
     */
    protected const UNASSIGNED_COUNTRY_TAB_TITLE = 'Countries to be unassigned';

    /**
     * @var string
     */
    protected const UNASSIGNED_COUNTRY_TAB_TEMPLATE = '@CountryGui/_partials/_tables/unassignment-country-table.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAssignedCountryTab($tabsViewTransfer)
            ->addDeassignmentCountryTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssignedCountryTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::ASSIGNED_COUNTRY_TAB_NAME)
            ->setTitle(static::ASSIGNED_COUNTRY_TAB_TITLE)
            ->setTemplate(static::ASSIGNED_COUNTRY_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addDeassignmentCountryTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::UNASSIGNED_COUNTRY_TAB_NAME)
            ->setTitle(static::UNASSIGNED_COUNTRY_TAB_TITLE)
            ->setTemplate(static::UNASSIGNED_COUNTRY_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
