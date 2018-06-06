<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException;

class ProductAlternativeListManager implements ProductAlternativeListManagerInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface
     */
    protected $productAlternativeListHydrator;

    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface
     */
    protected $productAlternativeReader;

    /**
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface $productAlternativeListHydrator
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface $productAlternativeReader
     */
    public function __construct(
        ProductAlternativeListHydratorInterface $productAlternativeListHydrator,
        ProductAlternativeReaderInterface $productAlternativeReader
    ) {
        $this->productAlternativeListHydrator = $productAlternativeListHydrator;
        $this->productAlternativeReader = $productAlternativeReader;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer
    {
        $productAlternativeCollection = $this
            ->productAlternativeReader
            ->getProductAlternativesByIdProductConcrete($idProductConcrete);

        return $this->hydrateProductAlternativeList(
            $productAlternativeCollection,
            new ProductAlternativeListTransfer()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductAlternativeListTransfer $productAlternativeListTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    protected function hydrateProductAlternativeList(
        ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer,
        ProductAlternativeListTransfer $productAlternativeListTransfer
    ): ProductAlternativeListTransfer {
        /** @var \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer */
        foreach ($productAlternativeCollectionTransfer->getProductAlternatives() as $productAlternativeTransfer) {
            $productAlternativeListTransfer->addProductAlternative(
                $this->resolveProductTypeHydration($productAlternativeTransfer)
            );
        }

        return $productAlternativeListTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @throws \Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function resolveProductTypeHydration(ProductAlternativeTransfer $productAlternativeTransfer): ProductAlternativeListItemTransfer
    {
        $productAlternativeTransfer->requireIdProduct();

        $idProductAbstract = $productAlternativeTransfer
            ->getIdProductAbstractAlternative();

        if ($idProductAbstract) {
            return $this
                ->productAlternativeListHydrator
                ->hydrateProductAbstractListItem(
                    $idProductAbstract,
                    new ProductAlternativeListItemTransfer()
                );
        }

        $idProductConcrete = $productAlternativeTransfer
            ->getIdProductConcreteAlternative();

        if ($idProductConcrete) {
            return $this
                ->productAlternativeListHydrator
                ->hydrateProductConcreteListItem(
                    $idProductConcrete,
                    new ProductAlternativeListItemTransfer()
                );
        }

        throw new ProductAlternativeIsNotDefinedException(
            'You must set an id of abstract or concrete product alternative.'
        );
    }
}
