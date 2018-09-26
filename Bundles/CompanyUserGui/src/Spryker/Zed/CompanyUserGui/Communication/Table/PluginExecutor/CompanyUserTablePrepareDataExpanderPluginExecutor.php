<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor;

class CompanyUserTablePrepareDataExpanderPluginExecutor implements CompanyUserTablePrepareDataExpanderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface[]
     */
    protected $companyUserTablePrepareDataExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyUserGuiExtension\Dependency\Plugin\CompanyUserGui\CompanyUserTablePrepareDataExpanderPluginInterface[] $companyUserTablePrepareDataExpanderPlugins
     */
    public function __construct(array $companyUserTablePrepareDataExpanderPlugins)
    {
        $this->companyUserTablePrepareDataExpanderPlugins = $companyUserTablePrepareDataExpanderPlugins;
    }

    /**
     * @param array $companyUserDataItem
     *
     * @return array
     */
    public function executePrepareDataExpanderPlugins(array $companyUserDataItem): array
    {
        $expandedCompanyUserData = [];
        foreach ($this->companyUserTablePrepareDataExpanderPlugins as $companyUserTablePrepareDataExpanderPlugin) {
            $expandedCompanyUserData += $companyUserTablePrepareDataExpanderPlugin->expandDataItem($companyUserDataItem);
        }

        return $expandedCompanyUserData;
    }
}
