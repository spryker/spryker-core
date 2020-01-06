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
    public const MIN_AVAILABLE_QUANTITY_FOR_AVAILABILITY = 1;

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
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return bool
     */
    public function isProductOfferAvailable(ProductOfferTransfer $productOfferTransfer, StoreTransfer $storeTransfer): bool
    {
        $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        return $this->availabilityFacade->isProductSellableForStore(
            $productOfferTransfer->getConcreteSku(),
            new Decimal(static::MIN_AVAILABLE_QUANTITY_FOR_AVAILABILITY),
            $storeTransfer,
            $productAvailabilityCriteriaTransfer
        );
    }
}
