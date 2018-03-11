<?php


namespace Spryker\Zed\CompanySupplier\Persistence;


use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProduct;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

class CompanySupplierEntityManager extends AbstractEntityManager implements CompanySupplierEntityManagerInterface
{
    public function saveCompanySuppliersForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $productConcreteTransfer->requireCompanySuppliers();

        foreach ($productConcreteTransfer->getCompanySuppliers() as $supplier) {
            $productCompanySupplier = new SpyCompanySupplierToProduct();
            $productCompanySupplier->setFkProduct($productConcreteTransfer->getIdProductConcrete());
            $productCompanySupplier->setFkCompany($supplier->getIdCompany());

            $productCompanySupplier->save();
        }
    }
}