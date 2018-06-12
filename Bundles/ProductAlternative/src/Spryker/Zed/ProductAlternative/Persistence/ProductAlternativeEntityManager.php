<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer;
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
    public function createProductAbstractAlternative(int $idProduct, int $idProductAbstractAlternative): ProductAlternativeTransfer
    {
        $productAlternativeEntityTransfer = (new SpyProductAlternativeEntityTransfer())
            ->setFkProduct($idProduct)
            ->setFkProductAbstractAlternative($idProductAbstractAlternative);

        return $this->createProductAlternative($productAlternativeEntityTransfer);
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
    public function createProductConcreteAlternative(int $idProduct, int $idProductConcreteAlternative): ProductAlternativeTransfer
    {
        $productAlternativeEntityTransfer = (new SpyProductAlternativeEntityTransfer())
            ->setFkProduct($idProduct)
            ->setFkProductConcreteAlternative($idProductConcreteAlternative);

        return $this->createProductAlternative($productAlternativeEntityTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function updateProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer
    {
        $productAlternativeEntityTransfer = $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransferToEntityTransfer($productAlternativeTransfer);

        $this->save($productAlternativeEntityTransfer);

        return $productAlternativeTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function createProductAlternative(SpyProductAlternativeEntityTransfer $productAlternativeEntityTransfer): ProductAlternativeTransfer
    {
        $productAlternativeEntityTransfer->setIdProductAlternative(null);

        $productAlternativeEntity = $this->getFactory()
            ->createProductAlternativeQuery()
            ->findOneOrCreate();

        $productAlternativeEntity = $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToEntity(
                $productAlternativeEntityTransfer,
                $productAlternativeEntity
            );

        $productAlternativeTransfer = $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityToTransfer($productAlternativeEntity);

        $productAlternativeTransfer->setIdProductAlternative(
            $productAlternativeEntityTransfer->getIdProductAlternative()
        );

        return $productAlternativeTransfer;
    }
}
