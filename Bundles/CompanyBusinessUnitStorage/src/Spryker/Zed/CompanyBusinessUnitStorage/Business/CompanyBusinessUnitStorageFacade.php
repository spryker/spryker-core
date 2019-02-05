<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitStorage\Business;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitStorage\Business\CompanyBusinessUnitStorageBusinessFactory getFactory()
 */
class CompanyBusinessUnitStorageFacade extends AbstractFacade implements CompanyBusinessUnitStorageFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     * @param \Generated\Shared\Transfer\CompanyUserStorageTransfer $companyUserStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer
     */
    public function addCompanyBusinessUnitId(CompanyUserTransfer $companyUserTransfer, CompanyUserStorageTransfer $companyUserStorageTransfer): CompanyUserStorageTransfer
    {
        return $this->getFactory()
            ->createCompanyUserStorageExpander()
            ->expandWithCompanyBusinessUnitId($companyUserStorageTransfer, $companyUserTransfer);
    }
}
