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
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface
     */
    protected $productAlternativeListSorter;

    /**
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface $productAlternativeListHydrator
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeReaderInterface $productAlternativeReader
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface $productAlternativeListSorter
     */
    public function __construct(
        ProductAlternativeListHydratorInterface $productAlternativeListHydrator,
        ProductAlternativeReaderInterface $productAlternativeReader,
        ProductAlternativeListSorterInterface $productAlternativeListSorter
    ) {
        $this->productAlternativeListHydrator = $productAlternativeListHydrator;
        $this->productAlternativeReader = $productAlternativeReader;
        $this->productAlternativeListSorter = $productAlternativeListSorter;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer
    {
        $productAlternativeCollection = $this->productAlternativeReader
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
        foreach ($productAlternativeCollectionTransfer->getProductAlternatives() as $productAlternativeTransfer) {
            $productAlternativeListTransfer->addProductAlternative(
                $this->resolveProductTypeHydration($productAlternativeTransfer)
            );
        }

        return $this->productAlternativeListSorter
            ->sortProductAlternativeList($productAlternativeListTransfer);
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

        $productAlternativeListItemTransfer = (new ProductAlternativeListItemTransfer())
            ->setIdProductAlternative(
                $productAlternativeTransfer->getIdProductAlternative()
            )
            ->setIdProduct(
                $productAlternativeTransfer->getIdProductAbstractAlternative()
                ?: $productAlternativeTransfer->getIdProductConcreteAlternative()
            );

        if ($idProductAbstract) {
            return $this->productAlternativeListHydrator
                ->hydrateProductAbstractListItem(
                    $idProductAbstract,
                    $productAlternativeListItemTransfer
                );
        }

        $idProductConcrete = $productAlternativeTransfer
            ->getIdProductConcreteAlternative();

        if ($idProductConcrete) {
            return $this->productAlternativeListHydrator
                ->hydrateProductConcreteListItem(
                    $idProductConcrete,
                    $productAlternativeListItemTransfer
                );
        }

        throw new ProductAlternativeIsNotDefinedException(
            'You must set an id of abstract or concrete product alternative.'
        );
    }
}
