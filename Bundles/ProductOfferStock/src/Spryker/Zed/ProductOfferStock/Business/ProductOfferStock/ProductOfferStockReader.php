<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\ProductOfferStock;

use Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer;
use Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferStockNotFoundException;
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
     * @param \Generated\Shared\Transfer\ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
     *
     * @throws \Spryker\Zed\ProductOfferStock\Business\Exception\ProductOfferStockNotFoundException
     *
     * @return bool
     */
    public function isProductOfferNeverOutOfStock(
        ProductOfferStockCriteriaFilterTransfer $productOfferStockCriteriaFilterTransfer
    ): bool {
        $productOfferStockCriteriaFilterTransfer->requireFkProductOffer();

        $productOfferTransfer = $this->productOfferStockRepository->findOne($productOfferStockCriteriaFilterTransfer);

        if (!$productOfferTransfer) {
            throw new ProductOfferStockNotFoundException(
                sprintf(
                    'Product offer stock with product offer foreign key "%s" not found!',
                    $productOfferStockCriteriaFilterTransfer->getFkProductOffer()
                )
            );
        }

        return $productOfferTransfer->getIsNeverOutOfStock();
    }
}
