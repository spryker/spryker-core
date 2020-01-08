<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\ProductOfferStock;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface;

class ProductOfferStockReader implements ProductOfferStockReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface
     */
    protected $productOfferStockRepository;

    /**
     * @param \Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface $productOfferStockRepository
     */
    public function __construct(ProductOfferStockRepositoryInterface $productOfferStockRepository)
    {
        $this->productOfferStockRepository = $productOfferStockRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStockRequestTransfer $productOfferStockRequestTransfer
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(ProductOfferStockRequestTransfer $productOfferStockRequestTransfer): bool
    {
        $productOfferStockRequestTransfer->requireProductOfferReference()
            ->requireStore()
            ->getStore()
                ->requireIdStore();

        $productOfferTransfer = $this->productOfferStockRepository->findOne($productOfferStockRequestTransfer);

        if (!$productOfferTransfer) {
            return false;
        }

        return $productOfferTransfer->getIsNeverOutOfStock();
    }
}
