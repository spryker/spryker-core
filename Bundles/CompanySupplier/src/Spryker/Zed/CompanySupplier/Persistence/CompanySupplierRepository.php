<?php


namespace Spryker\Zed\CompanySupplier\Persistence;

use ArrayObject;
use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanySupplier\Persistence\Map\SpyCompanySupplierToProductTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierPersistenceFactory getFactory()
 */
class CompanySupplierRepository extends AbstractRepository implements CompanySupplierRepositoryInterface
{

    public function getAllSuppliers(): array
    {
        return $this->buildQueryFromCriteria(
            $this->getFactory()->createCompanyQuery()
            //TODO: filter by company type
        )
        ->find();
    }

    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer
    {
        $query = $this->getFactory()->createCompanyQuery();
        $query->addJoin(SpyCompanyTableMap::COL_ID_COMPANY, SpyCompanySupplierToProductTableMap::COL_FK_COMPANY, Criteria::LEFT_JOIN);
        $query->where(SpyCompanySupplierToProductTableMap::COL_FK_PRODUCT . ' = '. $idProduct);
        $companySuppliers = new ArrayObject($this->buildQueryFromCriteria($query)->find());
        $result = new CompanySupplierCollectionTransfer();
        $result->setSuppliers($companySuppliers);

        return $result;}
}