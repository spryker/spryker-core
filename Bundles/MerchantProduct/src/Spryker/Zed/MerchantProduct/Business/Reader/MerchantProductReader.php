<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
}
