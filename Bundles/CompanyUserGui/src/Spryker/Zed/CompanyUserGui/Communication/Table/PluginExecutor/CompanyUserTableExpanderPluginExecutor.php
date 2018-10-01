<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor;

use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CompanyUserTableExpanderPluginExecutor implements CompanyUserTableExpanderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface[]
     */
    protected $companyUserTableConfigExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface[]
     */
    protected $companyUserTablePrepareDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTableConfigExpanderPluginInterface[] $companyUserTableConfigExpanderPlugins
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface[] $companyUserTablePrepareDataExpanderPlugins
     */
    public function __construct(
        array $companyUserTableConfigExpanderPlugins,
        array $companyUserTablePrepareDataExpanderPlugins
    ) {
        $this->companyUserTableConfigExpanderPlugins = $companyUserTableConfigExpanderPlugins;
        $this->companyUserTablePrepareDataExpanderPlugins = $companyUserTablePrepareDataExpanderPlugins;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    public function executeConfigExpanderPlugins(TableConfiguration $config): TableConfiguration
    {
        foreach ($this->companyUserTableConfigExpanderPlugins as $companyUserTableConfigExpanderPlugin) {
            $config = $companyUserTableConfigExpanderPlugin->expandConfig($config);
        }

        return $config;
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return array
     */
    public function executePrepareDataExpanderPlugins(array $companyUserDataItem): array
    {
        foreach ($this->companyUserTablePrepareDataExpanderPlugins as $companyUserTablePrepareDataExpanderPlugin) {
            $companyUserDataItem = $companyUserTablePrepareDataExpanderPlugin->expandDataItem($companyUserDataItem);
        }

        return $companyUserDataItem;
    }
}
