<?php


namespace Spryker\Zed\CompanySupplierGui\Communication\Plugin;


use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteFormEditDataProviderExpanderPluginInterface
{
    public function expand(ProductConcreteTransfer $productConcrete, array &$formData): void;
}