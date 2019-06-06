<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitStorage\Communication\Plugin;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserStorageExtension\Dependency\Plugin\CompanyUserStorageExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitStorage\Business\CompanyBusinessUnitStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CompanyBusinessUnitStorage\CompanyBusinessUnitStorageConfig getConfig()
 * @method \Spryker\Zed\CompanyBusinessUnitStorage\Communication\CompanyBusinessUnitStorageCommunicationFactory getFactory()
 */
class CompanyBusinessUnitCompanyUserStorageExpanderPlugin extends AbstractPlugin implements CompanyUserStorageExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *  - Expands CompanyUserStorageTransfer with company business unit id.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    public function expand(CompanyUserStorageTransfer $companyUserStorageTransfer, CompanyUserTransfer $companyUserTransfer): CompanyUserStorageTransfer
    {
        return $this->getFacade()
            ->expandWithCompanyBusinessUnitId($companyUserStorageTransfer, $companyUserTransfer);
    }
}
