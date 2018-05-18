<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 */
class CompanyBusinessUnitEntityManager extends AbstractEntityManager implements CompanyBusinessUnitEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function saveCompanyBusinessUnit(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitTransfer {
        $entityTransfer = $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapBusinessUnitTransferToEntityTransfer(
                $companyBusinessUnitTransfer,
                new SpyCompanyBusinessUnitEntityTransfer()
            );
        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createCompanyBusinessUnitMapper()
            ->mapEntityTransferToBusinessUnitTransfer(
                $entityTransfer,
                $companyBusinessUnitTransfer
            );
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function deleteCompanyBusinessUnitById(int $idCompanyBusinessUnit): void
    {
        $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit)
            ->delete();
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function clearParentBusinessUnit(int $idCompanyBusinessUnit): void
    {
        $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByIdParentCompanyBusinessUnit($idCompanyBusinessUnit)
            ->update(['IdParentCompanyBusinessUnit' => null]);
    }
}
