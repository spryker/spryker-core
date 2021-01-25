<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailabilityStorage\Dependency\Facade;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer;

class ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeBridge implements ProductOfferAvailabilityStorageToProductOfferAvailabilityFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityFacadeInterface
     */
    protected $productOfferAvailabilityFacade;

    /**
     * @param \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityFacadeInterface $productOfferAvailabilityFacade
     */
    public function __construct($productOfferAvailabilityFacade)
    {
        $this->productOfferAvailabilityFacade = $productOfferAvailabilityFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    public function findProductConcreteAvailabilityForRequest(
        ProductOfferAvailabilityRequestTransfer $productOfferAvailabilityRequestTransfer
    ): ?ProductConcreteAvailabilityTransfer {
        return $this->productOfferAvailabilityFacade->findProductConcreteAvailabilityForRequest($productOfferAvailabilityRequestTransfer);
    }
}
