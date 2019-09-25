<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class MerchantTablePluginExecutor implements MerchantTablePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface[]
     */
    protected $merchantTableActionExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface[]
     */
    protected $merchantTableDataExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface[]
     */
    protected $merchantTableHeaderExpanderPlugins;

    /**
     * @var \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface[]
     */
    protected $merchantTableConfigExpanderPlugins;

    /**
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableActionExpanderPluginInterface[] $merchantTableActionExpanderPlugins
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableHeaderExpanderPluginInterface[] $merchantTableHeaderExpanderPlugins
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableDataExpanderPluginInterface[] $merchantTableDataExpanderPlugins
     * @param \Spryker\Zed\MerchantGuiExtension\Dependency\Plugin\MerchantTableConfigExpanderPluginInterface[] $merchantTableConfigExpanderPlugins
     */
    public function __construct(
        array $merchantTableActionExpanderPlugins,
        array $merchantTableHeaderExpanderPlugins,
        array $merchantTableDataExpanderPlugins,
        array $merchantTableConfigExpanderPlugins
    ) {
        $this->merchantTableActionExpanderPlugins = $merchantTableActionExpanderPlugins;
        $this->merchantTableHeaderExpanderPlugins = $merchantTableHeaderExpanderPlugins;
        $this->merchantTableDataExpanderPlugins = $merchantTableDataExpanderPlugins;
        $this->merchantTableConfigExpanderPlugins = $merchantTableConfigExpanderPlugins;
    }

    /**
     * @return array
     */
    public function executeTableHeaderExpanderPlugins(): array
    {
        $expandedData = [];
        foreach ($this->merchantTableHeaderExpanderPlugins as $plugin) {
            $expandedData += $plugin->expandHeader();
        }

        return $expandedData;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeActionButtonExpanderPlugins(array $item): array
    {
        $buttonTransfers = [];
        foreach ($this->merchantTableActionExpanderPlugins as $merchantsTableExpanderPlugin) {
            $buttonTransfers = array_merge($buttonTransfers, $merchantsTableExpanderPlugin->getActionButtonDefinitions($item));
        }

        return $buttonTransfers;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeDataExpanderPlugins(array $item): array
    {
        $data = [];
        foreach ($this->merchantTableDataExpanderPlugins as $merchantTableDataExpanderPlugin) {
            $data = array_merge($data, $merchantTableDataExpanderPlugin->expandData($item));
        }

        return $data;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $tableConfiguration
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeConfigExpanderPlugins(TableConfiguration $tableConfiguration): TableConfiguration
    {
        foreach ($this->merchantTableConfigExpanderPlugins as $merchantTableConfigExpanderPlugin) {
            $tableConfiguration = $merchantTableConfigExpanderPlugin->expandConfig($tableConfiguration);
        }

        return $tableConfiguration;
    }
}
