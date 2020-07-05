<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Expander;

use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface
     */
    protected $priceProductOfferRepository;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface $priceProductOfferRepository
     */
    public function __construct(PriceProductOfferRepositoryInterface $priceProductOfferRepository)
    {
        $this->priceProductOfferRepository = $priceProductOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expandProductOfferWithPrices(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferTransfer->requireIdProductOffer();

        $productOfferTransfer->setPrices(
            $this->priceProductOfferRepository->getProductOfferPrices(
                (new PriceProductOfferCriteriaTransfer())->setIdProductOffer($productOfferTransfer->getIdProductOffer())
            )
        );

        return $productOfferTransfer;
    }
}
