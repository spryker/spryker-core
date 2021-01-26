<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Business\Deleter;

use Generated\Shared\Transfer\PriceProductOfferCollectionTransfer;
use Generated\Shared\Transfer\PriceProductOfferCriteriaTransfer;
use Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface;

class PriceProductOfferDeleter implements PriceProductOfferDeleterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface
     */
    protected $priceProductOfferEntityManager;

    /**
     * @param \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferEntityManagerInterface $priceProductOfferEntityManager
     */
    public function __construct(
        PriceProductOfferEntityManagerInterface $priceProductOfferEntityManager
    ) {
        $this->priceProductOfferEntityManager = $priceProductOfferEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer
     *
     * @return void
     */
    public function deleteProductOfferPrices(PriceProductOfferCollectionTransfer $priceProductOfferCollectionTransfer): void
    {
        $priceProductOfferCriteriaTransfer = new PriceProductOfferCriteriaTransfer();
        $priceProductOfferIds = [];
        /** @var \Generated\Shared\Transfer\PriceProductOfferTransfer $priceProductOfferTransfer */
        foreach ($priceProductOfferCollectionTransfer->getPriceProductOffers() as $priceProductOfferTransfer) {
            $priceProductOfferIds[] = $priceProductOfferTransfer->getIdPriceProductOfferOrFail();
        }
        $priceProductOfferCriteriaTransfer->setPriceProductOfferIds($priceProductOfferIds);
        $this->priceProductOfferEntityManager->delete($priceProductOfferCriteriaTransfer);
    }
}
