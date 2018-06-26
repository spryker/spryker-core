<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativePersistenceFactory getFactory()
 */
class ProductAlternativeEntityManager extends AbstractEntityManager implements ProductAlternativeEntityManagerInterface
{
    /**
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function saveProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer
    {
        $productAlternativeEntity = $this->getFactory()
            ->createProductAlternativePropelQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductAbstractAlternative($idProductAbstractAlternative)
            ->findOneOrCreate();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransfer($productAlternativeEntity);
    }

    /**
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function saveProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer
    {
        $productAlternativeEntity = $this->getFactory()
            ->createProductAlternativePropelQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductConcreteAlternative($idProductConcreteAlternative)
            ->findOneOrCreate();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransfer($productAlternativeEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function deleteProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        $productAlternative = $this->getFactory()
            ->createProductAlternativePropelQuery()
            ->filterByIdProductAlternative(
                $productAlternativeTransfer->getIdProductAlternative()
            )->findOne();

        $productAlternative->delete();
    }
}
