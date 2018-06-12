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
use Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface;

class ProductAlternativeReader implements ProductAlternativeReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface
     */
    protected $productAlternativeRepository;

    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface
     */
    protected $productAlternativeListSorter;

    /**
     * @var \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface
     */
    protected $productAlternativeListHydrator;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface $productAlternativeListSorter
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListHydratorInterface $productAlternativeListHydrator
     */
    public function __construct(
        ProductAlternativeRepositoryInterface $productAlternativeRepository,
        ProductAlternativeListSorterInterface $productAlternativeListSorter,
        ProductAlternativeListHydratorInterface $productAlternativeListHydrator
    ) {
        $this->productAlternativeRepository = $productAlternativeRepository;
        $this->productAlternativeListSorter = $productAlternativeListSorter;
        $this->productAlternativeListHydrator = $productAlternativeListHydrator;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeCollectionTransfer
     */
    public function getProductAlternativesByIdProductConcrete(int $idProductConcrete): ProductAlternativeCollectionTransfer
    {
        return $this->productAlternativeRepository
            ->getProductAlternativesForProductConcrete($idProductConcrete);
    }

    /**
     * @param int $idProductAlternative
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAlternativeByIdProductAlternative(int $idProductAlternative): ?ProductAlternativeTransfer
    {
        return $this->productAlternativeRepository
            ->findProductAlternativeByIdProductAlternative($idProductAlternative);
    }

    /**
     * @param int $idBaseProduct
     * @param int $idProductAbstract
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductAbstractAlternative(int $idBaseProduct, int $idProductAbstract): ?ProductAlternativeTransfer
    {
        return $this->productAlternativeRepository
            ->findProductAbstractAlternative($idBaseProduct, $idProductAbstract);
    }

    /**
     * @param int $idBaseProduct
     * @param int $idProductConcrete
     *
     * @return null|\Generated\Shared\Transfer\ProductAlternativeTransfer
     */
    public function getProductConcreteAlternative(int $idBaseProduct, int $idProductConcrete): ?ProductAlternativeTransfer
    {
        return $this->productAlternativeRepository
            ->findProductConcreteAlternative($idBaseProduct, $idProductConcrete);
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    public function getProductAlternativeListByIdProductConcrete(int $idProductConcrete): ProductAlternativeListTransfer
    {
        $productAlternativeCollection = $this->getProductAlternativesByIdProductConcrete($idProductConcrete);

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
