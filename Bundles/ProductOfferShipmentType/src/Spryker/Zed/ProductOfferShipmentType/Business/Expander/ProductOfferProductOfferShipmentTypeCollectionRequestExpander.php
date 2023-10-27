<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface;

class ProductOfferProductOfferShipmentTypeCollectionRequestExpander implements ProductOfferProductOfferShipmentTypeCollectionRequestExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ProductOfferReaderInterface $productOfferReader
     */
    public function __construct(
        ProductOfferExtractorInterface $productOfferExtractor,
        ProductOfferReaderInterface $productOfferReader
    ) {
        $this->productOfferExtractor = $productOfferExtractor;
        $this->productOfferReader = $productOfferReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer
     */
    public function expandWithProductOffersIds(
        ProductOfferShipmentTypeCollectionRequestTransfer $productOfferShipmentTypeCollectionRequestTransfer
    ): ProductOfferShipmentTypeCollectionRequestTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferShipmentTypeCollectionRequestTransfer->getProductOffers();

        $productOfferReferences = $this->productOfferExtractor->extractProductOfferReferencesFromProductOfferTransfers($productOfferTransfers);
        $productOfferCollectionTransfer = $this->productOfferReader->getProductOfferCollectionByProductOfferReferences($productOfferReferences);
        $productOfferTransfersIndexedByProductOfferReference = $this->getProductOfferTransfersIndexedByProductOfferReference($productOfferCollectionTransfer);

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $fetchedProductOfferTransfer = $productOfferTransfersIndexedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()] ?? null;
            if (!$fetchedProductOfferTransfer) {
                continue;
            }

            $productOfferTransfer->setIdProductOffer($fetchedProductOfferTransfer->getIdProductOfferOrFail());
        }

        return $productOfferShipmentTypeCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function getProductOfferTransfersIndexedByProductOfferReference(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOfferCollectionTransfersIndexedByProductOfferReference = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferCollectionTransfersIndexedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()] = $productOfferTransfer;
        }

        return $productOfferCollectionTransfersIndexedByProductOfferReference;
    }
}
