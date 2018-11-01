<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\CompanyTypeCollectionTransfer;
use Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Spryker\Shared\CompanySupplier\CompanySupplierConstants;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierPersistenceFactory getFactory()
 */
class CompanySupplierRepository extends AbstractRepository implements CompanySupplierRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanyTypeCollectionTransfer
     */
    public function getCompanyTypes(): CompanyTypeCollectionTransfer
    {
        $spyCompanyTypes = $this->buildQueryFromCriteria(
            $this->getFactory()->createCompanyTypeQuery()
        )->find();

        $spyCompanyTypes = new ArrayObject($spyCompanyTypes);
        $companyTypeCollection = new CompanyTypeCollectionTransfer();
        $companyTypeCollection->setCompanyTypes($spyCompanyTypes);

        return $companyTypeCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getAllSuppliers(): CompanySupplierCollectionTransfer
    {
        $query = $this->getFactory()->createCompanyQuery()
            ->leftJoinWithCompanyType()
            ->useCompanyTypeQuery()
                ->filterByName(CompanySupplierConstants::COMPANY_SUPPLIER_TYPE)
            ->endUse();

        return $this->getCompanySupplierCollectionFromQuery($query);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer
    {
        $query = $this->getFactory()->createCompanyQuery()
            ->leftJoinSpyCompanySupplierToProduct()
            ->useSpyCompanySupplierToProductQuery()
                ->filterByFkProduct($idProduct)
            ->endUse();

        return $this->getCompanySupplierCollectionFromQuery($query);
    }

    /**
     * @param \Orm\Zed\Company\Persistence\SpyCompanyQuery $query
     *
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    protected function getCompanySupplierCollectionFromQuery(SpyCompanyQuery $query): CompanySupplierCollectionTransfer
    {
        $companySuppliers = new ArrayObject(
            $this->buildQueryFromCriteria($query)->find()
        );
        $companySupplierCollection = new CompanySupplierCollectionTransfer();
        $companySupplierCollection->setSuppliers($companySuppliers);

        return $companySupplierCollection;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCompanyType
     *
     * @return \Generated\Shared\Transfer\SpyCompanyTypeEntityTransfer
     */
    public function getCompanyTypeByIdCompanyType(int $idCompanyType): SpyCompanyTypeEntityTransfer
    {
        $spyCompanyTypeEntityTransfer = $this->buildQueryFromCriteria(
            $this->getFactory()->createCompanyTypeQuery()->filterByIdCompanyType($idCompanyType)
        )->findOne();

        return $spyCompanyTypeEntityTransfer;
    }
}
