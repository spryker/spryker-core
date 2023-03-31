<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class StoreFormTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const GENERAL_TAB_NAME = 'general';

    /**
     * @var string
     */
    protected const GENERAL_TAB_TITLE = 'General';

    /**
     * @var string
     */
    protected const GENERAL_TAB_TEMPLATE = '@StoreGui/_partials/general-tab.twig';

    /**
     * @var string
     */
    protected const FOOTER_SUBMIT_TEMPLATE = '@StoreGui/_partials/_form-submit.twig';

    /**
     * @var array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface>
     */
    protected $storeFormTabExpanderPlugins;

    /**
     * @param array<\Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface> $storeFormTabExpanderPlugins
     */
    public function __construct(array $storeFormTabExpanderPlugins)
    {
        $this->storeFormTabExpanderPlugins = $storeFormTabExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addGeneralTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $this->executeStoreFormTabExpanderPlugins($tabsViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::GENERAL_TAB_NAME)
            ->setTitle(static::GENERAL_TAB_TITLE)
            ->setTemplate(static::GENERAL_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function executeStoreFormTabExpanderPlugins(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        foreach ($this->storeFormTabExpanderPlugins as $storeFormTabExpanderPlugin) {
            $tabsViewTransfer = $storeFormTabExpanderPlugin->expand($tabsViewTransfer);
        }

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function setFooter(TabsViewTransfer $tabsViewTransfer)
    {
        $tabsViewTransfer->setFooterTemplate(static::FOOTER_SUBMIT_TEMPLATE)
            ->setIsNavigable(true);

        return $this;
    }
}
