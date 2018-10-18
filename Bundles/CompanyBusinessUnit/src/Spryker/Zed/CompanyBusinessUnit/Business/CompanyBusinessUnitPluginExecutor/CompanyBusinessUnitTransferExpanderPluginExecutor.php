<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitPluginExecutor;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;

class CompanyBusinessUnitTransferExpanderPluginExecutor implements CompanyBusinessUnitTransferExpanderPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitTransferExpanderPluginInterface[]
     */
    protected $companyBusinessUnitTransferExpanderPlugins;

    /**
     * @param \Spryker\Zed\CompanyBusinessUnitExtension\Dependency\Plugin\CompanyBusinessUnitTransferExpanderPluginInterface[] $companyBusinessUnitTransferExpanderPlugins
     */
    public function __construct(array $companyBusinessUnitTransferExpanderPlugins)
    {
        $this->companyBusinessUnitTransferExpanderPlugins = $companyBusinessUnitTransferExpanderPlugins;
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
}
