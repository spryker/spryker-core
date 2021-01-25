<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StockGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class StockTabs extends AbstractTabs
{
    protected const TAB_CONFIGURATION_NAME = 'configuration';
    protected const TAB_CONFIGURATION_TITLE = 'Configuration';
    protected const TAB_CONFIGURATION_TEMPLATE = '@StockGui/_partials/_tabs/tab-configuration.twig';

    protected const TAB_STORE_RELATION_NAME = 'store-relation';
    protected const TAB_STORE_RELATION_TITLE = 'Store Relation';
    protected const TAB_STORE_RELATION_TEMPLATE = '@StockGui/_partials/_tabs/tab-store-relation.twig';

    protected const FOOTER_TEMPLATE = '@StockGui/_partials/form-submit.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addConfigurationTab($tabsViewTransfer)
            ->addStoreRelationTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addConfigurationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::TAB_CONFIGURATION_NAME);
        $tabItemTransfer->setTemplate(static::TAB_CONFIGURATION_TEMPLATE);
        $tabItemTransfer->setTitle(static::TAB_CONFIGURATION_TITLE);
        $tabItemTransfer->setHasError(true);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addStoreRelationTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::TAB_STORE_RELATION_NAME);
        $tabItemTransfer->setTemplate(static::TAB_STORE_RELATION_TEMPLATE);
        $tabItemTransfer->setTitle(static::TAB_STORE_RELATION_TITLE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer)
    {
        $tabsViewTransfer->setFooterTemplate(static::FOOTER_TEMPLATE)
            ->setIsNavigable(true);

        return $this;
    }
}
