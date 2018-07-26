<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory getFactory()
 */
class CompanyUnitAddressEntityManager extends AbstractEntityManager implements CompanyUnitAddressEntityManagerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function saveCompanyUnitAddress(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $entityTransfer = $this->getFactory()
            ->createCompanyUniAddressMapper()
            ->mapCompanyUnitAddressTransferToEntityTransfer(
                $companyUnitAddressTransfer,
                new SpyCompanyUnitAddressEntityTransfer()
            );
        $entityTransfer = $this->save($entityTransfer);
        $idCompanyUnitAddress = $entityTransfer->getIdCompanyUnitAddress();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $this->saveAddressToBusinessUnitRelations($companyUnitAddressTransfer, $idCompanyUnitAddress);

        return $companyUnitAddressTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompanyUnitAddress
     *
     * @return void
     */
    public function deleteCompanyUnitAddressById(int $idCompanyUnitAddress): void
    {
        $this->getFactory()
            ->createCompanyUnitAddressQuery()
            ->filterByIdCompanyUnitAddress($idCompanyUnitAddress)
            ->delete();
    }

    /**
     * @param array $idAddresses
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function assignToCompanyBusinessUnit(array $idAddresses, int $idCompanyBusinessUnit): void
    {
        foreach ($idAddresses as $idAddress) {
            $entityTransfer = new SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer();
            $entityTransfer->setFkCompanyBusinessUnit($idCompanyBusinessUnit)
                ->setFkCompanyUnitAddress($idAddress);

            $this->save($entityTransfer);
        }
    }

    /**
     * @param array $idAddresses
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function unAssignFromCompanyBusinessUnit(array $idAddresses, int $idCompanyBusinessUnit): void
    {
        if (count($idAddresses) === 0) {
            return;
        }

        $this->getFactory()
            ->createCompanyUnitAddressToCompanyBusinessUnitQuery()
            ->filterByFkCompanyBusinessUnit($idCompanyBusinessUnit)
            ->filterByFkCompanyUnitAddress_In($idAddresses)
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param int $idCompanyUnitAddress
     *
     * @return void
     */
    protected function saveAddressToBusinessUnitRelations(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        int $idCompanyUnitAddress
    ): void {
        $businessUnitCollection = $companyUnitAddressTransfer->getCompanyBusinessUnitCollection();

        if (!$businessUnitCollection || !$businessUnitCollection->getCompanyBusinessUnits()) {
            return;
        }

        foreach ($businessUnitCollection->getCompanyBusinessUnits() as $companyBusinessUnit) {
            $entityTransfer = new SpyCompanyUnitAddressToCompanyBusinessUnitEntityTransfer();
            $entityTransfer
                ->setFkCompanyBusinessUnit($companyBusinessUnit->getIdCompanyBusinessUnit())
                ->setFkCompanyUnitAddress($idCompanyUnitAddress);
            $this->save($entityTransfer);
        }
    }
}
