<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ErrorExtractorInterface;

class ProductOfferFilter implements ProductOfferFilterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(ErrorExtractorInterface $errorExtractor)
    {
        $this->errorExtractor = $errorExtractor;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $validProductOfferTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $invalidProductOfferTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function mergeProductOffers(
        ArrayObject $validProductOfferTransfers,
        ArrayObject $invalidProductOfferTransfers
    ): ArrayObject {
        foreach ($invalidProductOfferTransfers as $entityIdentifier => $invalidProductOfferTransfer) {
            $validProductOfferTransfers->offsetSet($entityIdentifier, $invalidProductOfferTransfer);
        }

        return $validProductOfferTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer>>
     */
    public function filterProductOffersByValidity(
        ProductOfferServiceCollectionResponseTransfer $productOfferServiceCollectionResponseTransfer
    ): array {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $productOfferServiceCollectionResponseTransfer->getErrors();
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers($errorTransfers);

        $validProductOfferTransfers = new ArrayObject();
        $invalidProductOfferTransfers = new ArrayObject();

        foreach ($productOfferServiceCollectionResponseTransfer->getProductOffers() as $entityIdentifier => $productOfferTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $invalidProductOfferTransfers->offsetSet($entityIdentifier, $productOfferTransfer);

                continue;
            }

            $validProductOfferTransfers->offsetSet($entityIdentifier, $productOfferTransfer);
        }

        return [$validProductOfferTransfers, $invalidProductOfferTransfers];
    }
}
