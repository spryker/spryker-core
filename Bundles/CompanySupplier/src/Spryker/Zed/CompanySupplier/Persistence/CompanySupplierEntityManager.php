<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySupplier\Persistence;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanySupplierToProduct;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\CompanySupplier\Persistence\CompanySupplierPersistenceFactory getFactory()
 */
class CompanySupplierEntityManager extends AbstractEntityManager implements CompanySupplierEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function saveCompanySupplierRelationsForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        $this->deleteCompanySupplierRelationsById($productConcreteTransfer->getIdProductConcrete());
        foreach ($productConcreteTransfer->getCompanySuppliers() as $supplier) {
            $productCompanySupplier = new SpyCompanySupplierToProduct();
            $productCompanySupplier->setFkProduct($productConcreteTransfer->getIdProductConcrete());
            $productCompanySupplier->setFkCompany($supplier->getIdCompany());

            $productCompanySupplier->save();
        }

        return $productConcreteTransfer;
    }

    /**
     * @param int $idProduct
     *
     * @return void
     */
    protected function deleteCompanySupplierRelationsById(int $idProduct): void
    {
        $this->getFactory()->createCompanySupplierToProductQuery()
            ->filterByFkProduct($idProduct)
            ->delete();
    }
}
