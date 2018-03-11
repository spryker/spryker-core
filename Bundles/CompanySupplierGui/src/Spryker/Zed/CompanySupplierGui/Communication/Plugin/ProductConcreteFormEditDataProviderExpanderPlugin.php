<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;


use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanySupplierGui\Communication\CompanySupplierGuiCommunicationFactory getFactory()
 */
class ProductConcreteFormEditDataProviderExpanderPlugin extends AbstractPlugin implements ProductConcreteFormEditDataProviderExpanderPluginInterface
{

    public function expand(ProductConcreteTransfer $productConcrete, array &$formData): void
    {
        $formData[CompanySupplierCollectionTransfer::SUPPLIERS] =
            $this->getFactory()->getCompanySupplierFacade()->getSuppliersByIdProduct($productConcrete->getIdProductConcrete())->getSuppliers();
    }
}