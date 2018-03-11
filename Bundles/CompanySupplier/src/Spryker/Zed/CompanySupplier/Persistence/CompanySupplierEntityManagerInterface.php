<?php


namespace Spryker\Zed\CompanySupplier\Persistence;


use Generated\Shared\Transfer\ProductConcreteTransfer;

interface CompanySupplierEntityManagerInterface
{
    public function saveCompanySuppliersForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void;
}