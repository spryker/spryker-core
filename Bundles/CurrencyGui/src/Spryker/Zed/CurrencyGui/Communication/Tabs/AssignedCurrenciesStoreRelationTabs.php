<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AssignedCurrenciesStoreRelationTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const ASSIGNED_CURRENCY_TAB_NAME = 'assigned_currency';

    /**
     * @var string
     */
    protected const ASSIGNED_CURRENCY_TAB_TITLE = 'Currencies in this list';

    /**
     * @var string
     */
    protected const ASSIGNED_CURRENCY_TAB_TEMPLATE = '@CurrencyGui/_partials/_tables/assigned-currency-table.twig';

    /**
     * @var string
     */
    protected const UNASSIGNED_CURRENCY_TAB_NAME = 'unassignment_currency';

    /**
     * @var string
     */
    protected const UNASSIGNED_CURRENCY_TAB_TITLE = 'Currencies to be unassigned';

    /**
     * @var string
     */
    protected const UNASSIGNED_CURRENCY_TAB_TEMPLATE = '@CurrencyGui/_partials/_tables/unassignment-currency-table.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAssignedCurrencyTab($tabsViewTransfer)
            ->addDeassignmentCurrencyTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssignedCurrencyTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::ASSIGNED_CURRENCY_TAB_NAME)
            ->setTitle(static::ASSIGNED_CURRENCY_TAB_TITLE)
            ->setTemplate(static::ASSIGNED_CURRENCY_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addDeassignmentCurrencyTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::UNASSIGNED_CURRENCY_TAB_NAME)
            ->setTitle(static::UNASSIGNED_CURRENCY_TAB_TITLE)
            ->setTemplate(static::UNASSIGNED_CURRENCY_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
