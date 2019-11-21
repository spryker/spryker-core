<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Gui\Communication\Tabs\AbstractTabs;

class MerchantFormTabs extends AbstractTabs
{
    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormTabExpanderPluginInterface[]
     */
    protected $merchantFormTabExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantFormTabExpanderPluginInterface[] $merchantFormTabExpanderPlugins
     */
    public function __construct(array $merchantFormTabExpanderPlugins)
    {
        $this->merchantFormTabExpanderPlugins = $merchantFormTabExpanderPlugins;
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

        return $this->executeMerchantFormTabExpanderPlugins($tabsViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addGeneralTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer->setName('general')
            ->setTitle('General')
            ->setTemplate('@MerchantGui/_partials/general-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function executeMerchantFormTabExpanderPlugins(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        foreach ($this->merchantFormTabExpanderPlugins as $merchantFormTabExpanderPlugin) {
            $tabsViewTransfer = $merchantFormTabExpanderPlugin->expand($tabsViewTransfer);
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
        $tabsViewTransfer->setFooterTemplate('@MerchantGui/_partials/_form-submit.twig')
            ->setIsNavigable(true);

        return $this;
    }
}
