<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

class CompanyBusinessUnitPluginExecutor implements CompanyBusinessUnitPluginExecutorInterface
{
    /**
     * @var array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitExpanderPluginInterface>
     */
    protected $companyBusinessUnitTransferExpanderPlugins;

    /**
     * @var array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPostSavePluginInterface>
     */
    protected $companyBusinessUnitPostSavePlugins;

    /**
     * @var array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface>
     */
    protected $companyBusinessUnitPreDeletePlugins;

    /**
     * @param array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitExpanderPluginInterface> $companyBusinessUnitTransferExpanderPlugins
     * @param array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPostSavePluginInterface> $companyBusinessUnitPostSavePlugins
     * @param array<\Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitPreDeletePluginInterface> $companyBusinessUnitPreDeletePlugins
     */
    public function __construct(
        array $companyBusinessUnitTransferExpanderPlugins,
        array $companyBusinessUnitPostSavePlugins,
        array $companyBusinessUnitPreDeletePlugins
    ) {
        $this->companyBusinessUnitTransferExpanderPlugins = $companyBusinessUnitTransferExpanderPlugins;
        $this->companyBusinessUnitPostSavePlugins = $companyBusinessUnitPostSavePlugins;
        $this->companyBusinessUnitPreDeletePlugins = $companyBusinessUnitPreDeletePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function executeTransferExpanderPlugins(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        foreach ($this->companyBusinessUnitTransferExpanderPlugins as $plugin) {
            $companyBusinessUnitTransfer = $plugin->expand($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function executePostSavePlugins(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): CompanyBusinessUnitTransfer
    {
        foreach ($this->companyBusinessUnitPostSavePlugins as $plugin) {
            $companyBusinessUnitTransfer = $plugin->postSave($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return void
     */
    public function executePreDeletePlugins(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): void
    {
        foreach ($this->companyBusinessUnitPreDeletePlugins as $plugin) {
            $plugin->preDelete($companyBusinessUnitTransfer);
        }
    }
}
