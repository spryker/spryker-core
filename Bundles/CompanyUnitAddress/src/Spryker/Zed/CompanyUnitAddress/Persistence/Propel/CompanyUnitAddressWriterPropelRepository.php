<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressWriterRepositoryInterface;

class CompanyUnitAddressWriterPropelRepository implements CompanyUnitAddressWriterRepositoryInterface
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
    public function save(CompanyUnitAddressTransfer $companyUnitAddressTransfer): CompanyUnitAddressTransfer
    {
        $companyUnitAddressEntity = $this->getFactory()
            ->createCompanyUniAddressMapper()
            ->mapCompanyUnitAddressTransferToEntity($companyUnitAddressTransfer);

        $companyUnitAddressEntity->save();

        return $this->getFactory()
            ->createCompanyUniAddressMapper()
            ->mapCompanyUnitAddressEntityToTransfer($companyUnitAddressEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return void
     */
    public function delete(CompanyUnitAddressTransfer $companyUnitAddressTransfer): void
    {
        $this->queryCompanyUnitAddress()
            ->filterByIdCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->delete();
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function queryCompanyUnitAddress(): SpyCompanyUnitAddressQuery
    {
        return $this->getFactory()->createCompanyUnitAddressQuery();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory
     */
    protected function getFactory()
    {
        return new CompanyUnitAddressPersistenceFactory();
    }
}
