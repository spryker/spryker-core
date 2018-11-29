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
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableConfigExpanderPluginInterface[]
     */
    protected $companyUserTableConfigExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTablePrepareDataExpanderPluginInterface[]
     */
    protected $companyUserTablePrepareDataExpanderPlugins;

    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionLinksExpanderPluginInterface[]
     */
    protected $companyUserTableActionLinksExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableConfigExpanderPluginInterface[] $companyUserTableConfigExpanderPlugins
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTablePrepareDataExpanderPluginInterface[] $companyUserTablePrepareDataExpanderPlugins
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserTableActionLinksExpanderPluginInterface[] $companyUserTableActionLinksExpanderPlugins
     */
    public function __construct(
        array $companyUserTableConfigExpanderPlugins,
        array $companyUserTablePrepareDataExpanderPlugins,
        array $companyUserTableActionLinksExpanderPlugins
    ) {
        $this->companyUserTableConfigExpanderPlugins = $companyUserTableConfigExpanderPlugins;
        $this->companyUserTablePrepareDataExpanderPlugins = $companyUserTablePrepareDataExpanderPlugins;
        $this->companyUserTableActionLinksExpanderPlugins = $companyUserTableActionLinksExpanderPlugins;
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

    /**
     * @param array $companyUserDataItem
     * @param \Generated\Shared\Transfer\ButtonTransfer[] $buttonTransfers
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer[]
     */
    public function executeActionExpanderPlugins(array $companyUserDataItem, array $buttonTransfers): array
    {
        foreach ($this->companyUserTableActionLinksExpanderPlugins as $companyUserTableActionExpanderPlugin) {
            $buttonTransfers = $companyUserTableActionExpanderPlugin->expandActionLinks($companyUserDataItem, $buttonTransfers);
        }

        return $buttonTransfers;
    }
}
