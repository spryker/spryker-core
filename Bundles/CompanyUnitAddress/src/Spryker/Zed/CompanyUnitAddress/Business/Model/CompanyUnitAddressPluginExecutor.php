<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Business\Model;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyUnitAddressPluginExecutor implements CompanyUnitAddressPluginExecutorInterface
{
    /**
     * @var \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressHydratePluginInterface[]
     */
    protected $companyUnitAddressHydratePlugins;

    /**
     * @var \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressPostSavePluginInterface[]
     */
    protected $companyUnitAddressPostSavePlugins;

    /**
     * @param \Spryker\Zed\CompanyUnitAddressLabel\Business\Model\CompanyUnitAddressHydratorInterface[] $companyUnitAddressHydratePlugins
     * @param \Spryker\Zed\CompanyUnitAddressExtension\Dependency\Plugin\CompanyUnitAddressPostSavePluginInterface[] $companyUnitAddressPostSavePlugins
     */
    public function __construct(
        array $companyUnitAddressHydratePlugins,
        array $companyUnitAddressPostSavePlugins
    ) {
        $this->companyUnitAddressHydratePlugins = $companyUnitAddressHydratePlugins;
        $this->companyUnitAddressPostSavePlugins = $companyUnitAddressPostSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function executeCompanyUnitAddressHydratorPlugins(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        foreach ($this->companyUnitAddressHydratePlugins as $plugin) {
            $companyUnitAddressTransfer = $plugin->hydrate($companyUnitAddressTransfer);
        }

        return $companyUnitAddressTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function executePostSavePlugins(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        foreach ($this->companyUnitAddressPostSavePlugins as $plugin) {
            $companyUnitAddressTransfer = $plugin->postSave($companyUnitAddressTransfer)
                ->getCompanyUnitAddressTransfer();
        }

        return $companyUnitAddressTransfer;
    }
}
