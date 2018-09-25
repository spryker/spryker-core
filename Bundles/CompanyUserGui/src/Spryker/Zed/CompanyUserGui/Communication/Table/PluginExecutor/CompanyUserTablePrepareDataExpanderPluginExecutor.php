<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserGui\Communication\Table\PluginExecutor;

use Generated\Shared\Transfer\CompanyUserTransfer;

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
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return array
     */
    public function executePrepareDataExpanderPlugins(CompanyUserTransfer $companyUserTransfer): array
    {
        $expandedCompanyUserData = [];
        foreach ($this->companyUserTablePrepareDataExpanderPlugins as $companyUserTablePrepareDataExpanderPlugin) {
            $expandedCompanyUserData += $companyUserTablePrepareDataExpanderPlugin->expandDataItem($companyUserTransfer);
        }

        return $expandedCompanyUserData;
    }
}
