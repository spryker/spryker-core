<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternative\Business\ProductAlternative;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductAlternativeCollectionTransfer;
use Generated\Shared\Transfer\ProductAlternativeListItemTransfer;
use Generated\Shared\Transfer\ProductAlternativeListTransfer;
use Generated\Shared\Transfer\ProductAlternativeTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface;
use Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface;
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
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductAlternative\Persistence\ProductAlternativeRepositoryInterface $productAlternativeRepository
     * @param \Spryker\Zed\ProductAlternative\Business\ProductAlternative\ProductAlternativeListSorterInterface $productAlternativeListSorter
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\ProductAlternative\Dependency\Facade\ProductAlternativeToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductAlternativeRepositoryInterface $productAlternativeRepository,
        ProductAlternativeListSorterInterface $productAlternativeListSorter,
        ProductAlternativeToLocaleFacadeInterface $localeFacade,
        ProductAlternativeToProductFacadeInterface $productFacade
    ) {
        $this->productAlternativeRepository = $productAlternativeRepository;
        $this->productAlternativeListSorter = $productAlternativeListSorter;
        $this->localeFacade = $localeFacade;
        $this->productFacade = $productFacade;
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
     * @throws \Spryker\Zed\ProductAlternative\Business\Exception\ProductAlternativeIsNotDefinedException
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListTransfer
     */
    protected function hydrateProductAlternativeList(
        ProductAlternativeCollectionTransfer $productAlternativeCollectionTransfer,
        ProductAlternativeListTransfer $productAlternativeListTransfer
    ): ProductAlternativeListTransfer {
        foreach ($productAlternativeCollectionTransfer->getProductAlternatives() as $productAlternativeTransfer) {
            $productAlternativeTransfer->requireIdProduct();

            $productAlternativeListItemTransfer = null;

            $productAlternativeTransferHasProductAbstractAlternative = $productAlternativeTransfer->getIdProductAbstractAlternative();
            $productAlternativeTransferHasProductConcreteAlternative = $productAlternativeTransfer->getIdProductConcreteAlternative();

            if (!$productAlternativeTransferHasProductAbstractAlternative
                && !$productAlternativeTransferHasProductConcreteAlternative) {
                throw new ProductAlternativeIsNotDefinedException(
                    'You must set an id of abstract or concrete product alternative.'
                );
            }

            if ($productAlternativeTransferHasProductAbstractAlternative) {
                $productAlternativeListItemTransfer = $this->hydrateProductAbstractListItemTransfer(
                    $productAlternativeTransfer
                );
            }

            if ($productAlternativeTransferHasProductConcreteAlternative) {
                $productAlternativeListItemTransfer = $this->hydrateProductConcreteListItemTransfer(
                    $productAlternativeTransfer
                );
            }

            $productAlternativeListTransfer->addProductAlternative($productAlternativeListItemTransfer);
        }

        return $this->productAlternativeListSorter
            ->sortProductAlternativeList($productAlternativeListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateProductAbstractListItemTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): ProductAlternativeListItemTransfer {
        $productAlternativeTransfer
            ->requireIdProduct()
            ->requireIdProductAbstractAlternative();

        $idProductAbstractAlternative = $productAlternativeTransfer->getIdProductAbstractAlternative();

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setIdProductAbstract($idProductAbstractAlternative)
            ->setIsActive(
                $this->productFacade->isProductActive($idProductAbstractAlternative)
            );

        $productAbstractListItemTransfer = $this->productAlternativeRepository
            ->getProductAlternativeListItemTransferForProductAbstract(
                $productAbstractTransfer,
                $this->localeFacade->getCurrentLocale()
            );

        return $this->finalizeProductAlternativeListItemTransfer(
            $productAbstractListItemTransfer,
            $productAlternativeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function hydrateProductConcreteListItemTransfer(
        ProductAlternativeTransfer $productAlternativeTransfer
    ): ProductAlternativeListItemTransfer {
        $productAlternativeTransfer
            ->requireIdProduct()
            ->requireIdProductConcreteAlternative();

        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setIdProductConcrete(
                $productAlternativeTransfer->getIdProductConcreteAlternative()
            );

        $productConcreteListItemTransfer = $this->productAlternativeRepository
            ->getProductAlternativeListItemTransferForProductConcrete(
                $productConcreteTransfer,
                $this->localeFacade->getCurrentLocale()
            );

        return $this->finalizeProductAlternativeListItemTransfer(
            $productConcreteListItemTransfer,
            $productAlternativeTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAlternativeListItemTransfer $productAlternativeListItemTransfer
     * @param \Generated\Shared\Transfer\ProductAlternativeTransfer $productAlternativeTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAlternativeListItemTransfer
     */
    protected function finalizeProductAlternativeListItemTransfer(
        ProductAlternativeListItemTransfer $productAlternativeListItemTransfer,
        ProductAlternativeTransfer $productAlternativeTransfer
    ): ProductAlternativeListItemTransfer {
        return $productAlternativeListItemTransfer
            ->setIdProductAlternative(
                $productAlternativeTransfer->getIdProductAlternative()
            )
            ->setIdProduct(
                $productAlternativeTransfer->getIdProductAbstractAlternative()
                    ?: $productAlternativeTransfer->getIdProductConcreteAlternative()
            );
    }
}
