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
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductAbstractAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function saveProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer
    {
        $productAlternativeEntity = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductAbstractAlternative($idProductAbstractAlternative)
            ->findOneOrCreate();

        $productAlternativeEntity
            ->setFkProduct($idProduct)
            ->setFkProductAbstractAlternative($idProductAbstractAlternative)
            ->save();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransfer($productAlternativeEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idProduct
     * @param int $idProductConcreteAlternative
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function saveProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer
    {
        $productAlternativeEntity = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByFkProduct($idProduct)
            ->filterByFkProductConcreteAlternative($idProductConcreteAlternative)
            ->findOneOrCreate();

        $productAlternativeEntity
            ->setFkProduct($idProduct)
            ->setFkProductConcreteAlternative($idProductConcreteAlternative)
            ->save();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransfer($productAlternativeEntity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return void
     */
    public function deleteProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): void
    {
        $productAlternative = $this->getFactory()
            ->createProductAlternativeQuery()
            ->filterByIdProductAlternative(
                $productAlternativeTransfer->getIdProductAlternative()
            )->findOne();

        $productAlternative->delete();
    }
}
