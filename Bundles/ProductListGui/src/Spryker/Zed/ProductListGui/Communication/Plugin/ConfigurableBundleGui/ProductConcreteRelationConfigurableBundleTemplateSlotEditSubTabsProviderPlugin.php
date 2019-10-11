<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Plugin\ConfigurableBundleGui;

use Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductConcreteRelationConfigurableBundleTemplateSlotEditSubTabsProviderPlugin extends AbstractPlugin implements ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface
{
    public const AVAILABLE_PRODUCT_CONCRETE_RELATION_TABS_NAME = 'availableProductConcreteRelationTabs';
    public const ASSIGNED_PRODUCT_CONCRETE_RELATION_TABS_NAME = 'assignedProductConcreteRelationTabs';

    /**
     * {@inheritDoc}
     * - Provides subtabs for Assign Products tab.
     *
     * @api
     *
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface[]
     */
    public function provideSubTabs(): array
    {
        return [
            static::AVAILABLE_PRODUCT_CONCRETE_RELATION_TABS_NAME => $this->getFactory()->createAvailableProductConcreteRelationTabs(),
            static::ASSIGNED_PRODUCT_CONCRETE_RELATION_TABS_NAME => $this->getFactory()->createAssignedProductConcreteRelationTabs(),
        ];
    }
}
