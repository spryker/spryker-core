<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class AssignedLocalesStoreRelationTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const ASSIGNED_LOCALE_TAB_NAME = 'assigned_locale';

    /**
     * @var string
     */
    protected const ASSIGNED_LOCALE_TAB_TITLE = 'Locales in this list';

    /**
     * @var string
     */
    protected const ASSIGNED_LOCALE_TAB_TEMPLATE = '@LocaleGui/_partials/_tables/assigned-locale-table.twig';

    /**
     * @var string
     */
    protected const UNASSIGNED_LOCALE_TAB_NAME = 'unassignment_locale';

    /**
     * @var string
     */
    protected const UNASSIGNED_LOCALE_TAB_TITLE = 'Locales to be unassigned';

    /**
     * @var string
     */
    protected const UNASSIGNED_LOCALE_TAB_TEMPLATE = '@LocaleGui/_partials/_tables/unassignment-locale-table.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addAssignedLocaleTab($tabsViewTransfer)
            ->addDeassignmentLocaleTab($tabsViewTransfer);

        $tabsViewTransfer->setIsNavigable(false);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addAssignedLocaleTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::ASSIGNED_LOCALE_TAB_NAME)
            ->setTitle(static::ASSIGNED_LOCALE_TAB_TITLE)
            ->setTemplate(static::ASSIGNED_LOCALE_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addDeassignmentLocaleTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName(static::UNASSIGNED_LOCALE_TAB_NAME)
            ->setTitle(static::UNASSIGNED_LOCALE_TAB_TITLE)
            ->setTemplate(static::UNASSIGNED_LOCALE_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
