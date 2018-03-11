<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplierGui\Dependency\Facade;

use Generated\Shared\Transfer\CompanySupplierCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;

interface CompanySupplierGuiToCompanySupplierFacadeInterface
{
    /**
     * @return array
     */
    public function getAllSuppliers(): array;

    public function getSuppliersByIdProduct(int $idProduct): CompanySupplierCollectionTransfer;

    public function saveCompanySuppliersForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): void;
}
