<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTypeTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanySupplier\Persistence\Map\SpyCompanySupplierToProductTableMap;
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
     * @return \Generated\Shared\Transfer\CompanySupplierCollectionTransfer
     */
    public function getAllSuppliers(): CompanySupplierCollectionTransfer
    {
        $query = $this->getFactory()->createCompanyQuery();
        $query->leftJoinWithCompanyType();
        $query->where(SpyCompanyTypeTableMap::COL_NAME . ' = ?', CompanySupplierConstants::COMPANY_SUPPLIER_TYPE);

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
        $query = $this->getFactory()->createCompanyQuery();
        $query->leftJoinSpyCompanySupplierToProduct();
        $query->where(SpyCompanySupplierToProductTableMap::COL_FK_PRODUCT . ' = ?', $idProduct);

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
}
