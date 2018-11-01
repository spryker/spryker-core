<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUnitAddressTablePluginExecutor implements CompanyUnitAddressTablePluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableConfigExpanderPluginInterface[]
     */
    protected $companyTableConfigExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableHeaderExpanderPluginInterface[]
     */
    protected $companyTableHeaderExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableDataExpanderPluginInterface[]
     */
    protected $companyTableDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableConfigExpanderPluginInterface[] $companyTableConfigExpanderPlugins
     * @param \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableHeaderExpanderPluginInterface[] $companyTableHeaderExpanderPlugins
     * @param \Spryker\Zed\CompanyUnitAddressGuiExtension\Dependency\Plugin\CompanyUnitAddressTableDataExpanderPluginInterface[] $companyTableDataExpanderPlugins
     */
    public function __construct(array $companyTableConfigExpanderPlugins, array $companyTableHeaderExpanderPlugins, array $companyTableDataExpanderPlugins)
    {
        $this->companyTableConfigExpanderPlugins = $companyTableConfigExpanderPlugins;
        $this->companyTableHeaderExpanderPlugins = $companyTableHeaderExpanderPlugins;
        $this->companyTableDataExpanderPlugins = $companyTableDataExpanderPlugins;
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
