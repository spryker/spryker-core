<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

class ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientBridge implements ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityClientInterface
     */
    protected $productOfferServicePointAvailabilityClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailability\ProductOfferServicePointAvailabilityClientInterface $productOfferServicePointAvailabilityClient
     */
    public function __construct($productOfferServicePointAvailabilityClient)
    {
        $this->productOfferServicePointAvailabilityClient = $productOfferServicePointAvailabilityClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function getProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        return $this->productOfferServicePointAvailabilityClient->getProductOfferServicePointAvailabilityCollection($productOfferServicePointAvailabilityCriteriaTransfer);
    }
}
