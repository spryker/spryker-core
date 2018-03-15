<?php


namespace Spryker\Zed\CompanySupplier\Persistence;


use Orm\Zed\CompanySupplier\Persistence\Map\SpyCompanySupplierToProductTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierPersistenceFactory getFactory()
 */
class CompanySupplierQueryContainer extends AbstractQueryContainer implements CompanySupplierQueryContainerInterface
{
    public function queryAProductSuppliers()
    {
        $query = $this->getFactory()->createProductQueryContainer();
        $query->addJoin(SpyProductTableMap::COL_ID_PRODUCT, SpyCompanySupplierToProductTableMap::COL_FK_PRODUCT,Criteria::RIGHT_JOIN);
        //$query->joinWithPriceProduct();

        return $query;
    }
}