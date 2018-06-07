<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinued\Persistence;

use Generated\Shared\Transfer\ProductDiscontinuedTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\ProductDiscontinued\Persistence\ProductDiscontinuedPersistenceFactory getFactory()
 */
class ProductDiscontinuedEntityManager extends AbstractEntityManager implements ProductDiscontinuedEntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return \Generated\Shared\Transfer\ProductDiscontinuedTransfer
     */
    public function saveProductDiscontinued(ProductDiscontinuedTransfer $productDiscontinuedTransfer): ProductDiscontinuedTransfer
    {
        $productDiscontinuedEntity = $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByIdProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
            ->findOneOrCreate();
        $productDiscontinuedEntity = $this->getFactory()
            ->createProductDiscontinuedMapper()
            ->mapTransferToEntity(
                $productDiscontinuedTransfer,
                $productDiscontinuedEntity
            );

        $productDiscontinuedEntity->save();
        $productDiscontinuedTransfer->setIdProductDiscontinued($productDiscontinuedEntity->getIdProductDiscontinued());

        return $productDiscontinuedTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function deleteProductDiscontinuedByProductId(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByFkProduct($productDiscontinuedTransfer->getFkProduct())
            ->findOne()
            ->delete();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductDiscontinuedTransfer $productDiscontinuedTransfer
     *
     * @return void
     */
    public function deleteProductDiscontinued(ProductDiscontinuedTransfer $productDiscontinuedTransfer): void
    {
        $this->getFactory()
            ->createProductDiscontinuedQuery()
            ->filterByIdProductDiscontinued($productDiscontinuedTransfer->getIdProductDiscontinued())
            ->findOne()
            ->delete();
    }
}
