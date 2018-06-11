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
        $spyProductAlternativeEntityTransfer = (new SpyProductAlternativeEntityTransfer())
            ->setFkProduct($idProduct)
            ->setFkProductAbstractAlternative($idProductAbstractAlternative);

        return $this->createProductAlternative($spyProductAlternativeEntityTransfer);
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
        $spyProductAlternativeEntityTransfer = (new SpyProductAlternativeEntityTransfer())
            ->setFkProduct($idProduct)
            ->setFkProductConcreteAlternative($idProductConcreteAlternative);

        return $this->createProductAlternative($spyProductAlternativeEntityTransfer);
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
        $spyProductAlternativeEntityTransfer = $this
            ->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransferToEntityTransfer($productAlternativeTransfer);

        $this->save($spyProductAlternativeEntityTransfer);

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
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery()
            ->filterByIdProductAlternative(
                $productAlternativeTransfer->getIdProductAlternative()
            )->findOne();

        $productAlternativeQuery->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $spyProductAlternativeEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    protected function createProductAlternative(SpyProductAlternativeEntityTransfer $spyProductAlternativeEntityTransfer): ProductAlternativeTransfer
    {
        $spyProductAlternativeEntityTransfer->setIdProductAlternative(null);

        /** @var \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $spyProductAlternativeEntityTransfer */
        $spyProductAlternativeEntityTransfer = $this->save($spyProductAlternativeEntityTransfer);

        $productAlternativeTransfer = $this
            ->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityTransferToTransfer($spyProductAlternativeEntityTransfer);

        $productAlternativeTransfer->setIdProductAlternative(
            $spyProductAlternativeEntityTransfer->getIdProductAlternative()
        );

        return $productAlternativeTransfer;
    }
}
