<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

class ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientBridge implements ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorClientInterface
     */
    protected $productOfferServicePointAvailabilityCalculatorClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\ProductOfferServicePointAvailabilityCalculatorClientInterface $productOfferServicePointAvailabilityCalculatorClient
     */
    public function __construct($productOfferServicePointAvailabilityCalculatorClient)
    {
        $this->productOfferServicePointAvailabilityCalculatorClient = $productOfferServicePointAvailabilityCalculatorClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): array {
        return $this->productOfferServicePointAvailabilityCalculatorClient->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
    }
}
