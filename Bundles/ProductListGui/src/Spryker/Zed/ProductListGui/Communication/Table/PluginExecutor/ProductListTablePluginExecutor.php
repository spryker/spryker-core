<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class ProductListTablePluginExecutor implements ProductListTablePluginExecutorInterface
{
    /**
     * @var array|\Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableActionExpanderInterface[]
     */
    protected $productListTableActionExpanderPlugins;

    /**
     * @var array|\Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableConfigExpanderPluginInterface[]
     */
    protected $productListTableConfigExpanderPlugins;

    /**
     * @var array|\Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface[]
     */
    protected $productListTableDataExpanderPlugins;

    /**
     * @var array|\Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableHeaderExpanderPluginInterface[]
     */
    protected $productListTableHeaderExpanderPlugins;

    /**
     * ProductListTablePluginExecutor constructor.
     *
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableActionExpanderInterface[] $productListTableActionExpanderPlugins
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableConfigExpanderPluginInterface[] $productListTableConfigExpanderPlugins
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableDataExpanderPluginInterface[] $productListTableDataExpanderPlugins
     * @param \Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListTableHeaderExpanderPluginInterface[] $productListTableHeaderExpanderPlugins
     */
    public function __construct(
        array $productListTableActionExpanderPlugins,
        array $productListTableConfigExpanderPlugins,
        array $productListTableDataExpanderPlugins,
        array $productListTableHeaderExpanderPlugins
    ) {
        $this->productListTableActionExpanderPlugins = $productListTableActionExpanderPlugins;
        $this->productListTableConfigExpanderPlugins = $productListTableConfigExpanderPlugins;
        $this->productListTableDataExpanderPlugins = $productListTableDataExpanderPlugins;
        $this->productListTableHeaderExpanderPlugins = $productListTableHeaderExpanderPlugins;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeTableActionExpanderPlugins(array $item): array
    {
        $buttons = [];
        foreach ($this->productListTableActionExpanderPlugins as $productListTableActionExpanderPlugin) {
            $buttons[] = $productListTableActionExpanderPlugin->prepareButton($item);
        }

        return $buttons;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeTableConfigExpanderPlugins(TableConfiguration $config): TableConfiguration
    {
        foreach ($this->productListTableConfigExpanderPlugins as $productListTableConfigExpanderPlugin) {
            $config = $productListTableConfigExpanderPlugin->expandConfig($config);
        }

        return $config;
    }

    /**
     * @return array
     */
    public function executeTableHeaderExpanderPlugins(): array
    {
        $expandedData = [];
        foreach ($this->productListTableHeaderExpanderPlugins as $productListTableHeaderExpanderPlugin) {
            $expandedData += $productListTableHeaderExpanderPlugin->expandHeader();
        }

        return $expandedData;
    }

    /**
     * @param array $item
     *
     * @return array
     */
    public function executeTableDataExpanderPlugins(array $item): array
    {
        $expandedData = [];
        foreach ($this->productListTableDataExpanderPlugins as $productListTableDataExpanderPlugin) {
            $expandedData += $productListTableDataExpanderPlugin->expandData($item);
        }

        return $expandedData;
    }
}
