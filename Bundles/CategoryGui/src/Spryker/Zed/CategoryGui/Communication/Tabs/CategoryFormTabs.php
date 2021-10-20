<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class CategoryFormTabs extends AbstractTabs
{
    /**
     * @var string
     */
    protected const NAME_SETTINGS = 'settings';

    /**
     * @var string
     */
    protected const TITLE_SETTING = 'Settings';

    /**
     * @var array<\Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface>
     */
    protected $categoryFormTabExpanderPlugins;

    /**
     * @param array<\Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface> $categoryFormTabExpanderPlugins
     */
    public function __construct(array $categoryFormTabExpanderPlugins)
    {
        $this->categoryFormTabExpanderPlugins = $categoryFormTabExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $this->addSettingTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $this->executeCategoryFormTabExpanderPlugins($tabsViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addSettingTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName(static::NAME_SETTINGS)
            ->setTitle(static::TITLE_SETTING)
            ->setTemplate('@CategoryGui/_partials/settings-tab.twig');

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
        $tabsViewTransfer
            ->setFooterTemplate('@CategoryGui/_partials/tabs-footer.twig')
            ->setIsNavigable(true);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function executeCategoryFormTabExpanderPlugins(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        foreach ($this->categoryFormTabExpanderPlugins as $categoryFormTabExpanderPlugin) {
            $tabsViewTransfer = $categoryFormTabExpanderPlugin->expand($tabsViewTransfer);
        }

        return $tabsViewTransfer;
    }
}
