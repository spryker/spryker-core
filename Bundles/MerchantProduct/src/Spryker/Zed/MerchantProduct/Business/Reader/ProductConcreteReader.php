<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Reader;

use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductConcreteCollectionTransfer;
use Spryker\Zed\MerchantProduct\Dependency\Facade\MerchantProductToProductFacadeInterface;
use Spryker\Zed\MerchantProduct\Persistence\MerchantProductRepositoryInterface;

class ProductConcreteReader implements ProductConcreteReaderInterface
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
     * @return \Generated\Shared\Transfer\ProductConcreteCollectionTransfer
     */
    public function getProductConcreteCollection(
        MerchantProductCriteriaTransfer $merchantProductCriteriaTransfer
    ): ProductConcreteCollectionTransfer {
        $merchantProductCriteriaTransfer->requireIdMerchant();
        $productConcreteIds = $merchantProductCriteriaTransfer->getProductConcreteIds();
        $productConcreteCollectionTransfer = new ProductConcreteCollectionTransfer();

        if (!count($productConcreteIds)) {
            return $productConcreteCollectionTransfer;
        }

        $merchantProductCollectionTransfer = $this->merchantProductRepository->get($merchantProductCriteriaTransfer);

        $productAbstractIds = array_map(function (MerchantProductTransfer $merchantProductTransfer): int {
            return (int)$merchantProductTransfer->getIdProductAbstract();
        }, $merchantProductCollectionTransfer->getMerchantProducts()->getArrayCopy());

        $productConcreteTransfers = [];
        foreach ($productAbstractIds as $idProductAbstract) {
            $productConcreteTransfers += $this->productFacade->getConcreteProductsByAbstractProductId($idProductAbstract);
        }

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (in_array($productConcreteTransfer->getIdProductConcrete(), $productConcreteIds)) {
                $productConcreteCollectionTransfer->addProduct($productConcreteTransfer);
            }
        }

        return $productConcreteCollectionTransfer;
    }
}
