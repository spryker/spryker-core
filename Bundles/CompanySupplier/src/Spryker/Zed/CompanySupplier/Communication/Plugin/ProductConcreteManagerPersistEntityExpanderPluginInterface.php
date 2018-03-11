<?php


namespace Spryker\Zed\CompanySupplier\Communication\Plugin;


use Generated\Shared\Transfer\ProductConcreteTransfer;

interface ProductConcreteManagerPersistEntityExpanderPluginInterface
{
    public function persistRelatedData(ProductConcreteTransfer $productConcrete): void;
}