<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOffer;

use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToAvailabilityFacadeInterface;

class ProductOfferAvailabilityChecker implements ProductOfferAvailabilityCheckerInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToAvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct(
        MerchantProductOfferStorageToAvailabilityFacadeInterface $availabilityFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductOfferAvailable(ProductOfferTransfer $productOfferTransfer, StoreTransfer $storeTransfer): bool
    {
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())->setStore($storeTransfer)
            ->setProductOffer($productOfferTransfer)
            ->setSku($productOfferTransfer->getConcreteSku())
            ->setQuantity(new Decimal(0));

        return $this->availabilityFacade->isProductSellableForStore(
            $productAvailabilityCriteriaTransfer->getSku(),
            $productAvailabilityCriteriaTransfer->getQuantity(),
            $productAvailabilityCriteriaTransfer->getStore(),
            $productAvailabilityCriteriaTransfer
        );
    }
}
