<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ErrorExtractorInterface;

class ProductOfferFilter implements ProductOfferFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(ErrorExtractorInterface $errorExtractor)
    {
        $this->errorExtractor = $errorExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>>
     */
    public function filterProductOffersByValidity(
        ProductOfferShipmentTypeCollectionResponseTransfer $productOfferShipmentTypeCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $productOfferShipmentTypeCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validProductOfferTransfers = new ArrayObject();
        $invalidProductOfferTransfers = new ArrayObject();

        foreach ($productOfferShipmentTypeCollectionResponseTransfer->getProductOffers() as $entityIdentifier => $productOfferTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidProductOfferTransfers->offsetSet($entityIdentifier, $productOfferTransfer);

                continue;
            }

            $validProductOfferTransfers->offsetSet($entityIdentifier, $productOfferTransfer);
        }

        return [$validProductOfferTransfers, $invalidProductOfferTransfers];
    }
}
