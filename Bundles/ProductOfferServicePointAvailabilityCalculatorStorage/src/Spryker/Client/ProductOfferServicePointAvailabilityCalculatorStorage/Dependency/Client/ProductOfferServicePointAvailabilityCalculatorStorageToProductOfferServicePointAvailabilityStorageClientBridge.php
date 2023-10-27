<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;

class ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientBridge implements ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\ProductOfferServicePointAvailabilityStorageClientInterface
     */
    protected $productOfferServicePointAvailabilityStorageClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\ProductOfferServicePointAvailabilityStorageClientInterface $productOfferServicePointAvailabilityStorageClient
     */
    public function __construct($productOfferServicePointAvailabilityStorageClient)
    {
        $this->productOfferServicePointAvailabilityStorageClient = $productOfferServicePointAvailabilityStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function getProductOfferServicePointAvailabilityCollection(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        return $this->productOfferServicePointAvailabilityStorageClient->getProductOfferServicePointAvailabilityCollection($productOfferServicePointAvailabilityCriteriaTransfer);
    }
}
