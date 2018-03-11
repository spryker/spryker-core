<?php


namespace Spryker\Zed\CompanySupplier\Communication\Plugin;


use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProduct;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ProductConcreteManagerPersistEntityExpanderPlugin extends AbstractPlugin implements ProductConcreteManagerPersistEntityExpanderPluginInterface
{

    public function persistRelatedData(ProductConcreteTransfer $productConcrete): void
    {
        $productConcrete->requireCompanySuppliers();

        foreach ($productConcrete->getCompanySuppliers() as $supplier) {
            $productCompanySupplier = new SpyCompanySupplierToProduct();
            $productCompanySupplier->setFkProduct($productConcrete->getIdProductConcrete());
            $productCompanySupplier->setFkCompany($supplier->getIdCompany());

            $productCompanySupplier->save();
        }
    }
}