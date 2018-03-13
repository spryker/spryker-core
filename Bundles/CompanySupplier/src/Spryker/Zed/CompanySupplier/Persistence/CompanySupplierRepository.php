<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\CompanySupplier\Persistence\Map\SpyCompanySupplierToProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

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
        //TODO: filter by company type

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
        $query->addJoin(SpyCompanyTableMap::COL_ID_COMPANY, SpyCompanySupplierToProductTableMap::COL_FK_COMPANY, Criteria::LEFT_JOIN);
        $query->where(SpyCompanySupplierToProductTableMap::COL_FK_PRODUCT . ' = ' . $idProduct);

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

    public function getAllProductSuppliers()
    {
        $query = $this->getFactory()->createProductQueryContainer();
//        $query->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyCompanySupplierToProductTableMap::COL_FK_PRODUCT,Criteria::RIGHT_JOIN);
//        $query->joinWithStockProduct();
        $result = $query->find();
//        $result = $this->buildQueryFromCriteria($query)->find();
//        $this->populateCollectionWithRelation($result, 'SpyStockProduct');

        return $result;

//        return $this->getCompanySupplierCollectionFromQuery($query);
    }
}
