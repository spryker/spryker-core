<?php


namespace Spryker\Zed\CompanySupplier\Persistence;


use Spryker\Zed\Kernel\Persistence\AbstractRepository;

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
}