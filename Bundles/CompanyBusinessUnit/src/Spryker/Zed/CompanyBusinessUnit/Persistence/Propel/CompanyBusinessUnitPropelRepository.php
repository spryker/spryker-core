<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Persistence\Propel;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\Repository\RepositoryCollectionHandlerTrait;

class CompanyBusinessUnitPropelRepository implements CompanyBusinessUnitRepositoryInterface
{
    use RepositoryCollectionHandlerTrait;

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    public function getCompanyBusinessUnitById(
        CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
    ): CompanyBusinessUnitResponseTransfer {
        $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();

        $companyBusinessUnitEntity = $this->queryCompanyBusinessUnit()
            ->filterByIdCompanyBusinessUnit(
                $companyBusinessUnitTransfer->getIdCompanyBusinessUnit()
            )->findOne();

        $companyBusinessUnitTransfer = $this->getFactory()->createCompanyBusinessUnitMapper()
            ->mapCompanyBusinessUnitEntityToTransfer($companyBusinessUnitEntity);

        $companyBusinessUnitResponseTransfer = new CompanyBusinessUnitResponseTransfer();
        $companyBusinessUnitResponseTransfer->setCompanyBusinessUnitTransfer($companyBusinessUnitTransfer);

        return $companyBusinessUnitResponseTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    public function getCompanyBusinessUnitCollection(
        CompanyBusinessUnitCollectionTransfer $businessUnitCollectionTransfer
    ): CompanyBusinessUnitCollectionTransfer {

        $businessUnitCollectionTransfer->requireIdCompany();

        $query = $this->queryCompanyBusinessUnit()->filterByFkCompany(
            $businessUnitCollectionTransfer->getIdCompany()
        );

        $query = $this->mergeQueryWithFilter($query, $businessUnitCollectionTransfer->getFilter());
        $companyBusinessUnitsCollection = $this->getCollection($query, $businessUnitCollectionTransfer);

        $companyBusinessUnits = $this->hydrateCompanyBusinessUnitCollectionFromEntityCollection($companyBusinessUnitsCollection);
        $businessUnitCollectionTransfer->setCompanyBusinessUnits($companyBusinessUnits);

        return $businessUnitCollectionTransfer;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitTransfer $companyBusinessUnitTransfer
     *
     * @return bool
     */
    public function isCompanyBusinessUnitHasUsers(CompanyBusinessUnitTransfer $companyBusinessUnitTransfer): bool
    {
        $companyBusinessUnitTransfer->requireIdCompanyBusinessUnit();

        $count = $this->queryCompanyBusinessUnit()
            ->filterByIdCompanyBusinessUnit($companyBusinessUnitTransfer->getIdCompanyBusinessUnit())
            ->joinWithCompanyUser()
            ->count();

        return ($count > 0);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $companyBusinessUnitsCollection
     *
     * @return \ArrayObject
     */
    protected function hydrateCompanyBusinessUnitCollectionFromEntityCollection(
        ObjectCollection $companyBusinessUnitsCollection
    ): ArrayObject {
        $companyBusinessUnits = new ArrayObject();
        foreach ($companyBusinessUnitsCollection as $companyBusinessUnitEntity) {
            $companyBusinessUnit = $this->getFactory()->createCompanyBusinessUnitMapper()
                ->mapCompanyBusinessUnitEntityToTransfer($companyBusinessUnitEntity);
            $companyBusinessUnits->append($companyBusinessUnit);
        }

        return $companyBusinessUnits;
    }

    /**
     * @return \Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery
     */
    protected function queryCompanyBusinessUnit(): SpyCompanyBusinessUnitQuery
    {
        return $this->getFactory()->createCompanyBusinessUnitQuery();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitPersistenceFactory
     */
    protected function getFactory(): CompanyBusinessUnitPersistenceFactory
    {
        return new CompanyBusinessUnitPersistenceFactory();
    }
}
