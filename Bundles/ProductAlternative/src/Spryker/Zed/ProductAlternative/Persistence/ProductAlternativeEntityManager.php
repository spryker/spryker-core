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
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function createProductAlternative(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer
    {
        $spyProductAlternativeEntityTransfer = $this
            ->getFactory()
            ->createProductAlternativeMapper()
            ->mapProductAlternativeTransferToEntityTransfer($productAlternativeTransfer);

        $spyProductAlternativeEntityTransfer->setIdProductAlternative(null);

        /** @var \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer $spyProductAlternativeEntityTransfer */
        $spyProductAlternativeEntityTransfer = $this->save($spyProductAlternativeEntityTransfer);

        $productAlternativeTransfer->setIdProductAlternative(
            $spyProductAlternativeEntityTransfer->getIdProductAlternative()
        );

        return $productAlternativeTransfer;
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
}
