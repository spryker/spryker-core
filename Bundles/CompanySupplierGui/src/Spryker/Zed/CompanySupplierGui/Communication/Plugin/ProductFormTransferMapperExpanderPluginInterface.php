<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;


use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductFormTransferMapperExpanderPluginInterface
{
    public function map(ProductConcreteTransfer &$productConcrete, array $formData): void;
}