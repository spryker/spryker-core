<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProductCriteriaTransfer;
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

        $merchantProductCollectionTransfer = $this->merchantProductRepository->get($merchantProductCriteriaTransfer);

        $productConcreteIds = [];
        foreach ($merchantProductCollectionTransfer->getMerchantProducts() as $merchantProductTransfer) {
            $productConcreteIds = array_merge($productConcreteIds, $merchantProductTransfer->getProductConcreteIds());
        }

        $productConcreteTransfers = $this->productFacade->getProductConcreteTransfersByProductIds($productConcreteIds);

        $productConcreteCollectionTransfer = (new ProductConcreteCollectionTransfer())
            ->setProducts(new ArrayObject($productConcreteTransfers));

        return $productConcreteCollectionTransfer;
    }
}
