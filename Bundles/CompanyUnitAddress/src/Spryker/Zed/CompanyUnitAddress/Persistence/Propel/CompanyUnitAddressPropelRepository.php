<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddress\Persistence\Propel;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory;
use Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressRepositoryInterface;
use Spryker\Zed\CompanyUnitAddress\Persistence\Propel\Repository\RepositoryCollectionHandlerTrait;

class CompanyUnitAddressPropelRepository implements CompanyUnitAddressRepositoryInterface
{
    use RepositoryCollectionHandlerTrait;

    /**
     * Specification:
     * - Returns the business units for the given company and filters.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    public function getCompanyUnitAddressCollection(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): CompanyUnitAddressCollectionTransfer {
        $query = $this->queryCompanyUnitAddressCollection($companyUnitAddressCollectionTransfer);

        $companyUnitAddressCollection = $this->getCollection($query, $companyUnitAddressCollectionTransfer);

        $businessUnitAddresses = $this->getFactory()
            ->createCompanyUnitAddressHydrator()
            ->hydrateUnitAddressCollectionEntityCollection($companyUnitAddressCollection);

        $companyUnitAddressCollectionTransfer->setCompanyUnitAddresses($businessUnitAddresses);

        return $companyUnitAddressCollectionTransfer;
    }

    /**
     * Specification:
     * - Finds a company unit address by CompanyUnitAddressTransfer::idCompanyUnitAddress in the transfer
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function getCompanyUnitAddressById(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer
    ): CompanyUnitAddressTransfer {
        $companyUnitAddressEntity = $this->queryCompanyUnitAddress()
            ->filterByIdCompanyUnitAddress($companyUnitAddressTransfer->getIdCompanyUnitAddress())
            ->findOne();

        return $this->getFactory()
            ->createCompanyUniAddressMapper()
            ->mapCompanyUnitAddressEntityToTransfer($companyUnitAddressEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
     *
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function queryCompanyUnitAddressCollection(
        CompanyUnitAddressCollectionTransfer $companyUnitAddressCollectionTransfer
    ): SpyCompanyUnitAddressQuery {
        $companyUnitAddressCollectionTransfer->requireFkCompany();

        $query = $this->queryCompanyUnitAddress()
            ->filterByFkCompany($companyUnitAddressCollectionTransfer->getFkCompany());

        if ($companyUnitAddressCollectionTransfer->getFkCompanyBusinessUnit() !== null) {
            $query->filterByFkCompanyBusinessUnit($companyUnitAddressCollectionTransfer->getFkCompanyBusinessUnit());
        }

        return $this->mergeQueryWithFilter($query, $companyUnitAddressCollectionTransfer->getFilter());
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Persistence\CompanyUnitAddressPersistenceFactory
     */
    protected function getFactory()
    {
        return new CompanyUnitAddressPersistenceFactory();
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function queryCompanyUnitAddress(): SpyCompanyUnitAddressQuery
    {
        return $this->getFactory()->createCompanyUnitAddressQuery();
    }
}
