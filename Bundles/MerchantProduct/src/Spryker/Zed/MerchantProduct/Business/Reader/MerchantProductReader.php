<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\MerchantProduct\Business\Exception\EmptyRequiredPropertyException;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;

class MerchantProductReader implements MerchantProductReaderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface
     */
    protected $merchantProductRepository;

    /**
     * @var \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface $merchantProductRepository
     * @param \Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface $productFacade
     */
    public function __construct(
        MerchantProductRepositoryInterface $merchantProductRepository,
        MerchantProductToProductFacadeInterface $productFacade
    ) {
        $this->merchantProductRepository = $merchantProductRepository;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductTransfer|null
     */
    public function findMerchantProduct(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?MerchantProductTransfer {
        $merchantProductTransfer = $this->merchantProductRepository->findMerchantProduct($merchantProductCriteriaTransfer);

        if (!$merchantProductTransfer || $merchantProductTransfer->getIdProductAbstract() === null) {
            return null;
        }

        $merchantProductTransfer->setProductAbstract(
            $this->productFacade->findProductAbstractById($merchantProductTransfer->getIdProductAbstract())
        );

        return $merchantProductTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        $merchantProductCriteriaTransfer->addIdMerchant($merchantProductCriteriaTransfer->getIdMerchantOrFail());

        $merchantProductCollectionTransfer = $this->merchantProductRepository->get($merchantProductCriteriaTransfer);

        $productConcreteIds = [];
        foreach ($merchantProductCollectionTransfer->getMerchantProducts() as $merchantProductTransfer) {
            $merchantProductConcreteIds = array_map(function (ProductConcreteTransfer $productConcreteTransfer) {
                return $productConcreteTransfer->getIdProductConcreteOrFail();
            }, $merchantProductTransfer->getProducts()->getArrayCopy());

            $productConcreteIds = array_merge($productConcreteIds, $merchantProductConcreteIds);
        }

        $productConcreteTransfers = $this->productFacade->getProductConcreteTransfersByProductIds($productConcreteIds);

        return (new ProductConcreteCollectionTransfer())->setProducts(new ArrayObject($productConcreteTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
     *
     * @throws \Spryker\Zed\MerchantProduct\Business\Exception\EmptyRequiredPropertyException
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|null
     */
    public function findProductConcrete(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ?ProductConcreteTransfer {
        if (!count($merchantProductCriteriaTransfer->getProductConcreteIds())) {
            throw new EmptyRequiredPropertyException(MerchantProductCriteriaTransfer::PRODUCT_CONCRETE_IDS);
        }

        $merchantProductTransfer = $this->merchantProductRepository->findMerchantProduct($merchantProductCriteriaTransfer);

        if (!$merchantProductTransfer || !$merchantProductTransfer->getProducts()->count()) {
            return null;
        }

        $idProductConcretes = $merchantProductCriteriaTransfer->getProductConcreteIds();

        foreach ($merchantProductTransfer->getProducts() as $productConcreteTransfer) {
            foreach ($idProductConcretes as $idProductConcrete) {
                if ($idProductConcrete === $productConcreteTransfer->getIdProductConcreteOrFail()) {
                    return $this->productFacade->findProductConcreteById(
                        $productConcreteTransfer->getIdProductConcreteOrFail()
                    );
                }
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return bool
     */
    public function isProductConcreteOwnedByMerchant(
        ProductConcreteTransfer $productConcreteTransfer,
        MerchantTransfer $merchantTransfer
    ): bool {
        $merchantProductCriteriaTransfer = (new MerchantProductCriteriaTransfer())
            ->addIdMerchant($merchantTransfer->getIdMerchantOrFail())
            ->addIdProductConcrete($productConcreteTransfer->getIdProductConcreteOrFail());

        $merchantTransfer = $this->merchantProductRepository->findMerchant($merchantProductCriteriaTransfer);

        return $merchantTransfer !== null;
    }
}
