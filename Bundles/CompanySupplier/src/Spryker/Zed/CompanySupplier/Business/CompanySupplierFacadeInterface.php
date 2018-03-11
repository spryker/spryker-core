<?php

namespace Spryker\Zed\CompanySupplier\Business;

use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface CompanySupplierFacadeInterface
{

    public function getAllSuppliers(): array;

    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer;

    public function saveCompanySuppliersForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void;

}
