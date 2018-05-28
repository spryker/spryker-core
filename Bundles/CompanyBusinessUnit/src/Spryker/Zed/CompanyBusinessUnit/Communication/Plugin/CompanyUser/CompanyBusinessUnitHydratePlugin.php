<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\CompanyUser;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\CompanyUserExtension\Dependency\Plugin\CompanyUserHydrationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Company business unit is already populated by CompanyUser module
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface getFacade()
 */
class CompanyBusinessUnitHydratePlugin extends AbstractPlugin implements CompanyUserHydrationPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function hydrate(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        if ($companyUserTransfer->getFkCompanyBusinessUnit() !== null) {
            $businessUnitTransfer = new CompanyBusinessUnitTransfer();
            $businessUnitTransfer->setIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());
            $businessUnitTransfer = $this->getFacade()->getCompanyBusinessUnitById($businessUnitTransfer);
            $companyUserTransfer->setCompanyBusinessUnit($businessUnitTransfer);
        }

        return $companyUserTransfer;
    }
}
