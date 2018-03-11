<?php

namespace Spryker\Zed\CompanySupplier\Business;

use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanySupplier\Business\CompanySupplierBusinessFactory getFactory()
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierRepository getRepository()
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierEntityManager getEntityManager()
 */
class CompanySupplierFacade extends AbstractFacade implements CompanySupplierFacadeInterface
{

    public function getAllSuppliers(): array
    {
        return $this->getRepository()->getAllSuppliers();
    }

    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer
    {
        return $this->getRepository()->getSuppliersByIdProduct($idProduct);
    }

    public function saveCompanySuppliersForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $this->getEntityManager()->saveCompanySuppliersForProductConcrete($productConcreteTransfer);
    }

}
