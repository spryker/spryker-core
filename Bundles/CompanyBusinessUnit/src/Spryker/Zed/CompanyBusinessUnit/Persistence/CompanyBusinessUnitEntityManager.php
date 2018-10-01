<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Mapper\CompanyBusinessUnitMapperInterface;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory getFactory()
 * @method \Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer save(\Generated\Shared\Transfer\SpyCompanyBusinessUnitEntityTransfer $spyCompanyBusinessUnitEntityTransfer)
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
        $entityTransfer = $this->getMapper()->mapBusinessUnitTransferToEntityTransfer(
            $companyBusinessUnitTransfer,
            new SpyCompanyBusinessUnitEntityTransfer()
        );
        $entityTransfer = $this->save($entityTransfer);

        if ($companyBusinessUnitTransfer->isPropertyModified(CompanyBusinessUnitTransfer::FK_PARENT_COMPANY_BUSINESS_UNIT) &&
            $companyBusinessUnitTransfer->getFkParentCompanyBusinessUnit() === null
        ) {
            $this->clearParentBusinessUnitByCompanyBusinessUnitId($entityTransfer->getIdCompanyBusinessUnit());
        }

        return $this->getMapper()->mapEntityTransferToBusinessUnitTransfer(
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
            ->filterByFkParentCompanyBusinessUnit($idCompanyBusinessUnit)
            ->update(['FkParentCompanyBusinessUnit' => null]);
    }

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function clearParentBusinessUnitByCompanyBusinessUnitId(int $idCompanyBusinessUnit): void
    {
        $this->getFactory()
            ->createCompanyBusinessUnitQuery()
            ->filterByIdCompanyBusinessUnit($idCompanyBusinessUnit)
            ->update(['FkParentCompanyBusinessUnit' => null]);
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\Mapper\CompanyBusinessUnitMapperInterface
     */
    protected function getMapper(): CompanyBusinessUnitMapperInterface
    {
        return $this->getFactory()->createCompanyBusinessUnitMapper();
    }
}
