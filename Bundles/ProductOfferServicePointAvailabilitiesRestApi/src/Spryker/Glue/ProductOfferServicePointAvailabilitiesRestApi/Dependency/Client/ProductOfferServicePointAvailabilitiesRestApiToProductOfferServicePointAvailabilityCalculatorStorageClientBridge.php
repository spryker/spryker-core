<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

class ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorStorageClientBridge implements ProductOfferServicePointAvailabilitiesRestApiToProductOfferServicePointAvailabilityCalculatorStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\ProductOfferServicePointAvailabilityCalculatorStorageClientInterface
     */
    protected $productOfferServicePointAvailabilityCalculatorStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\ProductOfferServicePointAvailabilityCalculatorStorageClientInterface $productOfferServicePointAvailabilityCalculatorStorageClient
     */
    public function __construct($productOfferServicePointAvailabilityCalculatorStorageClient)
    {
        $this->productOfferServicePointAvailabilityCalculatorStorageClient = $productOfferServicePointAvailabilityCalculatorStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): array {
        return $this->productOfferServicePointAvailabilityCalculatorStorageClient->calculateProductOfferServicePointAvailabilities(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
    }
}
