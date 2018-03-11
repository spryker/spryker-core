<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;


use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

class ProductFormTransferMapperExpanderPlugin extends AbstractPlugin implements ProductFormTransferMapperExpanderPluginInterface
{

    public function map(ProductConcreteTransfer &$productConcrete, array $formData): void
    {
        $productConcrete->setCompanySuppliers($formData[CompanySupplierCollectionTransfer::SUPPLIERS]);
    }
}