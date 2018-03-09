<?php

namespace Spryker\Zed\CompanySupplier\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanySupplier\Business\CompanySupplierBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierRepository getRepository()
 */
class CompanySupplierFacade extends AbstractFacade implements CompanySupplierFacadeInterface
{

    public function getAllSuppliers(): array
    {
        return $this->getRepository()->getAllSuppliers();
    }

}
