<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStock\Business\Expander;

use Generated\Shared\Transfer\ProductOfferStockRequestTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferStock\Persistence\ProductOfferStockRepositoryInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
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
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithProductOfferStock(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferTransfer->requireProductOfferReference();

        $productOfferStockRequestTransfer = (new ProductOfferStockRequestTransfer())->setProductOfferReference(
            $productOfferTransfer->getProductOfferReference()
        );

        $productOfferTransfer->setProductOfferStock(
            $this->productOfferStockRepository->findOne($productOfferStockRequestTransfer)
        );

        return $productOfferTransfer;
    }
}
