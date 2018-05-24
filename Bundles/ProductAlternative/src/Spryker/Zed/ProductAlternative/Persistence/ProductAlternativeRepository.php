<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Persistence;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativePersistenceFactory getFactory()
 */
class ProductAlternativeRepository extends AbstractRepository implements ProductAlternativeRepositoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesForProductConcrete(ProductConcreteTransfer $productConcreteTransfer): ProductAlternativeCollectionTransfer
    {
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery();

        $alternativeProducts = $productAlternativeQuery
            ->filterByIdProductAlternative(
                $productConcreteTransfer->getIdProductConcrete()
            )->find();

        return $this->hydrateProductAlternativeCollectionWithProductAlternatives($alternativeProducts);
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
    public function getProductAlternativeByProductAlternativeId(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeTransfer
    {
        $productAlternativeQuery = $this
            ->getFactory()
            ->createProductAlternativeQuery();

        $alternativeProduct = $productAlternativeQuery
            ->filterByIdProductAlternative(
                $productAlternativeTransfer->getIdProductAlternative()
            )->findOne();

        return $this->getFactory()
            ->createProductAlternativeMapper()
            ->mapSpyProductAlternativeEntityToTransfer($alternativeProduct);
    }

    /**
     * @param array $productAlternatives
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    protected function hydrateProductAlternativeCollectionWithProductAlternatives(array $productAlternatives): ProductAlternativeCollectionTransfer
    {
        $productAlternativeCollectionTransfer = new ProductAlternativeCollectionTransfer();

        foreach ($productAlternatives as $productAlternative) {
            $productAlternativeCollectionTransfer->addProductAlternative($productAlternative);
        }

        return $productAlternativeCollectionTransfer;
    }
}
