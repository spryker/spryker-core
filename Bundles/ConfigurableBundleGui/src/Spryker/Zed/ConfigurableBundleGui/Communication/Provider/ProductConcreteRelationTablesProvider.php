<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleGui\Communication\Provider;

use Spryker\Zed\ConfigurableBundleGui\Communication\Exception\MissingTablesException;

class ProductConcreteRelationTablesProvider implements ProductConcreteRelationTablesProviderInterface
{
    /**
     * @var array<string>
     */
    protected const REQUIRED_TABLES = [
        'availableProductConcreteTable',
        'assignedProductConcreteTable',
    ];

    /**
     * @var array<\Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTablesProviderPluginInterface>
     */
    protected $configurableBundleTemplateSlotEditTablesProviderPlugins;

    /**
     * @param array<\Spryker\Zed\ConfigurableBundleGuiExtension\Dependency\Plugin\ConfigurableBundleTemplateSlotEditTablesProviderPluginInterface> $configurableBundleTemplateSlotEditTablesProviderPlugins
     */
    public function __construct(array $configurableBundleTemplateSlotEditTablesProviderPlugins)
    {
        $this->configurableBundleTemplateSlotEditTablesProviderPlugins = $configurableBundleTemplateSlotEditTablesProviderPlugins;
    }

    /**
     * @return array<\Spryker\Zed\Gui\Communication\Table\AbstractTable>
     */
    public function getTables(): array
    {
        /** @var array<\Spryker\Zed\Gui\Communication\Table\AbstractTable> $configurableBundleTemplateSlotEditTables */
        $configurableBundleTemplateSlotEditTables = [];

        foreach ($this->configurableBundleTemplateSlotEditTablesProviderPlugins as $configurableBundleTemplateSlotEditTablesProviderPlugin) {
            $configurableBundleTemplateSlotEditTables = array_merge($configurableBundleTemplateSlotEditTables, $configurableBundleTemplateSlotEditTablesProviderPlugin->provideTables());
        }

        $this->ensureRequiredTablesExist($configurableBundleTemplateSlotEditTables);

        return $configurableBundleTemplateSlotEditTables;
    }

    /**
     * @param array<\Spryker\Zed\Gui\Communication\Table\AbstractTable> $configurableBundleTemplateSlotEditTables
     *
     * @throws \Spryker\Zed\ConfigurableBundleGui\Communication\Exception\MissingTablesException
     *
     * @return void
     */
    protected function ensureRequiredTablesExist(array $configurableBundleTemplateSlotEditTables): void
    {
        foreach (static::REQUIRED_TABLES as $requiredTable) {
            if (!array_key_exists($requiredTable, $configurableBundleTemplateSlotEditTables)) {
                throw new MissingTablesException('Required Product Concrete Relation tables are missing.');
            }
        }
    }
}
