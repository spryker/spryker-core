<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\SpyCompanyUnitAddressEntityTransfer;
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

        return $this->getFactory()
            ->createCompanyUniAddressMapper()
            ->mapEntityTransferToCompanyUnitAddressTransfer(
                $entityTransfer,
                $companyUnitAddressTransfer
            );
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
}
