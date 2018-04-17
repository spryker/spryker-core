<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyTablePluginExecutor implements CompanyTablePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableConfigExpanderPluginInterface[]
     */
    protected $companyTableConfigExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableHeaderExpanderPluginInterface[]
     */
    protected $companyTableHeaderExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableDataExpanderPluginInterface[]
     */
    protected $companyTableDataExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableActionExpanderInterface[]
     */
    protected $companyTableActionExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableConfigExpanderPluginInterface[] $companyTableConfigExpanderPlugins
     * @param \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableHeaderExpanderPluginInterface[] $companyTableHeaderExpanderPlugins
     * @param \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableDataExpanderPluginInterface[] $companyTableDataExpanderPlugins
     * @param \Spryker\Zed\CompanyGuiExtension\Dependency\Plugin\CompanyTableActionExpanderInterface[] $companyTableActionExpanderPlugins
     */
    public function __construct(array $companyTableConfigExpanderPlugins, array $companyTableHeaderExpanderPlugins, array $companyTableDataExpanderPlugins, array $companyTableActionExpanderPlugins)
    {
        $this->companyTableConfigExpanderPlugins = $companyTableConfigExpanderPlugins;
        $this->companyTableHeaderExpanderPlugins = $companyTableHeaderExpanderPlugins;
        $this->companyTableDataExpanderPlugins = $companyTableDataExpanderPlugins;
        $this->companyTableActionExpanderPlugins = $companyTableActionExpanderPlugins;
    }

    /**
     * @param array $item
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeTableActionExpanderPlugins(array $item): array
    {
        $buttons = [];
        foreach ($this->companyTableActionExpanderPlugins as $companyTableActionExtensionPlugin) {
            $buttons[] = $companyTableActionExtensionPlugin->prepareButton($item);
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
        foreach ($this->companyTableConfigExpanderPlugins as $plugin) {
            $config = $plugin->expandConfig($config);
        }

        return $config;
    }

    /**
     * @return array
     */
    public function executeTableHeaderExpanderPlugins(): array
    {
        $expandedData = [];
        foreach ($this->companyTableHeaderExpanderPlugins as $plugin) {
            $expandedData += $plugin->expandHeader();
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
        foreach ($this->companyTableDataExpanderPlugins as $plugin) {
            $expandedData += $plugin->expandData($item);
        }

        return $expandedData;
    }
}
