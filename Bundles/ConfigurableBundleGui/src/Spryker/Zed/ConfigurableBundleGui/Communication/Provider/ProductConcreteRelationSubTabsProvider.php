<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Provider;

use Spryker\Zed\ConfigurableBundleGui\Communication\Exception\MissingTabsException;

class ProductConcreteRelationSubTabsProvider implements ProductConcreteRelationSubTabsProviderInterface
{
    protected const REQUIRED_SUB_TABS = [
        'availableProductConcreteRelationTabs',
        'assignedProductConcreteRelationTabs',
    ];

    /**
     * @var \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface[]
     */
    protected $configurableBundleTemplateSlotEditSubTabsProviderPlugins;

    /**
     * @param \Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditSubTabsProviderPluginInterface[] $configurableBundleTemplateSlotEditSubTabsProviderPlugins
     */
    public function __construct(array $configurableBundleTemplateSlotEditSubTabsProviderPlugins)
    {
        $this->configurableBundleTemplateSlotEditSubTabsProviderPlugins = $configurableBundleTemplateSlotEditSubTabsProviderPlugins;
    }

    /**
     * @return \Spryker\Zed\Gui\Communication\Tabs\TabsInterface[]
     */
    public function getSubTabs(): array
    {
        $configurableBundleTemplateSlotEditSubTabs = [];

        foreach ($this->configurableBundleTemplateSlotEditSubTabsProviderPlugins as $configurableBundleTemplateSlotEditSubTabsProviderPlugin) {
            $configurableBundleTemplateSlotEditSubTabs = array_merge($configurableBundleTemplateSlotEditSubTabs, $configurableBundleTemplateSlotEditSubTabsProviderPlugin->provideSubTabs());
        }

        $this->ensureRequiredSubTabsExist($configurableBundleTemplateSlotEditSubTabs);

        return $configurableBundleTemplateSlotEditSubTabs;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Tabs\TabsInterface[] $configurableBundleTemplateSlotEditSubTabs
     *
     * @throws \Spryker\Zed\ConfigurableBundleGui\Communication\Exception\MissingTabsException
     *
     * @return void
     */
    protected function ensureRequiredSubTabsExist(array $configurableBundleTemplateSlotEditSubTabs): void
    {
        foreach (static::REQUIRED_SUB_TABS as $requiredSubTab) {
            if (!array_key_exists($requiredSubTab, $configurableBundleTemplateSlotEditSubTabs)) {
                throw new MissingTabsException('Required Product Concrete Relation subtabs are missing.');
            }
        }
    }
}
