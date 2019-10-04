<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui;

use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTabsExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListManagementConfigurableBundleTemplateSlotEditTabsExpanderPlugin extends AbstractPlugin implements ConfigurableBundleTemplateSlotEditTabsExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands ConfigurableBundleTemplateSlotEditTabs with Producc List assignment tabs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        return $this->getFactory()
            ->createProductListCreateAggregationTabsExpander()
            ->expandWithProductListAssignmentTabs($tabsViewTransfer);
    }
}
