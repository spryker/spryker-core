<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Expander\SellableItemsResponseExpanderInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor\SellableItemRequestExtractorInterface;
use Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter\SellableItemRequestFilterInterface;

class ProductOfferServicePointAvailabilityReader implements ProductOfferServicePointAvailabilityReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor\SellableItemRequestExtractorInterface
     */
    protected SellableItemRequestExtractorInterface $sellableItemRequestExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServiceReaderInterface
     */
    protected ProductOfferServiceReaderInterface $productOfferServiceReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Business\Expander\SellableItemsResponseExpanderInterface
     */
    protected SellableItemsResponseExpanderInterface $sellableItemsResponseExpander;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter\SellableItemRequestFilterInterface
     */
    protected SellableItemRequestFilterInterface $sellableItemRequestFilter;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferReaderInterface $productOfferReader
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Business\Extractor\SellableItemRequestExtractorInterface $sellableItemRequestExtractor
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Business\Reader\ProductOfferServiceReaderInterface $productOfferServiceReader
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Business\Expander\SellableItemsResponseExpanderInterface $sellableItemsResponseExpander
     * @param \Spryker\Zed\ProductOfferServicePointAvailability\Business\Filter\SellableItemRequestFilterInterface $sellableItemRequestFilter
     */
    public function __construct(
        ProductOfferReaderInterface $productOfferReader,
        SellableItemRequestExtractorInterface $sellableItemRequestExtractor,
        ProductOfferServiceReaderInterface $productOfferServiceReader,
        SellableItemsResponseExpanderInterface $sellableItemsResponseExpander,
        SellableItemRequestFilterInterface $sellableItemRequestFilter
    ) {
        $this->productOfferReader = $productOfferReader;
        $this->sellableItemRequestExtractor = $sellableItemRequestExtractor;
        $this->productOfferServiceReader = $productOfferServiceReader;
        $this->sellableItemsResponseExpander = $sellableItemsResponseExpander;
        $this->sellableItemRequestFilter = $sellableItemRequestFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function getItemsAvailabilityForStore(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\SellableItemRequestTransfer> $sellableItemRequestTransfers */
        $sellableItemRequestTransfers = $sellableItemsRequestTransfer->getSellableItemRequests();
        $sellableItemRequestTransfers = $this->sellableItemRequestFilter->filterSellableItemRequestTransfersWithProductOfferReferenceAndServicePoint(
            $sellableItemRequestTransfers,
        );

        if (!$sellableItemRequestTransfers->count()) {
            return $sellableItemsResponseTransfer;
        }

        $productOfferReferences = $this->sellableItemRequestExtractor->extractProductOfferReferencesFromSellableItemRequestTransfers(
            $sellableItemRequestTransfers->getArrayCopy(),
        );
        $productOfferCollectionTransfer = $this->productOfferReader->getProductOfferCollectionByProductOfferReferences($productOfferReferences);
        $productOfferIdsIndexedByProductOfferReference = $this->getProductOfferIdsIndexedByProductOfferReference($productOfferCollectionTransfer);
        $productOfferServiceCollection = $this->productOfferServiceReader->getProductOfferServiceCollectionByProductOfferIds(
            array_values($productOfferIdsIndexedByProductOfferReference),
        );
        $productOfferIdsGroupedByIdServicePoint = $this->getProductOfferIdsGroupedByIdServicePoint($productOfferServiceCollection);

        foreach ($sellableItemRequestTransfers as $sellableItemRequestTransfer) {
            $productAvailabilityCriteriaTransfer = $sellableItemRequestTransfer->getProductAvailabilityCriteriaOrFail();
            $idProductOffer = $productOfferIdsIndexedByProductOfferReference[$productAvailabilityCriteriaTransfer->getProductOfferReferenceOrFail()];
            $idServicePoint = $productAvailabilityCriteriaTransfer->getServicePointOrFail()->getIdServicePointOrFail();

            if (
                isset($productOfferIdsGroupedByIdServicePoint[$idServicePoint])
                && in_array($idProductOffer, $productOfferIdsGroupedByIdServicePoint[$idServicePoint], true)
            ) {
                continue;
            }

            $sellableItemRequestTransfer->setIsProcessed(true);
            $sellableItemsResponseTransfer = $this->sellableItemsResponseExpander->expandSellableItemsResponseWithNotSellableItem(
                $sellableItemsResponseTransfer,
                $sellableItemRequestTransfer,
            );
        }

        return $sellableItemsResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return list<\Generated\Shared\Transfer\SellableItemRequestTransfer>
     */
    protected function getSellableItemRequestTransfersToProcess(SellableItemsRequestTransfer $sellableItemsRequestTransfer): array
    {
        $sellableItemRequestTransfers = [];
        foreach ($sellableItemsRequestTransfer->getSellableItemRequests() as $sellableItemRequestTransfer) {
            if (!$this->isSellableItemRequestTransferValid($sellableItemRequestTransfer)) {
                continue;
            }

            $sellableItemRequestTransfers[] = $sellableItemRequestTransfer;
        }

        return $sellableItemRequestTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemRequestTransfer $sellableItemRequestTransfer
     *
     * @return bool
     */
    protected function isSellableItemRequestTransferValid(SellableItemRequestTransfer $sellableItemRequestTransfer): bool
    {
        $productAvailabilityCriteriaTransfer = $sellableItemRequestTransfer->getProductAvailabilityCriteria();

        if (!$productAvailabilityCriteriaTransfer) {
            return false;
        }

        return $productAvailabilityCriteriaTransfer->getProductOfferReference() && $productAvailabilityCriteriaTransfer->getServicePoint();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<string, int>
     */
    protected function getProductOfferIdsIndexedByProductOfferReference(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOfferIdsIndexedByProductOfferReference = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferIdsIndexedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()] = $productOfferTransfer->getIdProductOfferOrFail();
        }

        return $productOfferIdsIndexedByProductOfferReference;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollection
     *
     * @return array<int, list<int>>
     */
    protected function getProductOfferIdsGroupedByIdServicePoint(ProductOfferServiceCollectionTransfer $productOfferServiceCollection): array
    {
        $productOfferIdsGroupedByIdServicePoint = [];

        foreach ($productOfferServiceCollection->getProductOfferServices() as $productOfferServiceTransfer) {
            foreach ($productOfferServiceTransfer->getServices() as $serviceTransfer) {
                $productOfferIdsGroupedByIdServicePoint[$serviceTransfer->getServicePointOrFail()->getIdServicePointOrFail()][]
                    = $productOfferServiceTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            }
        }

        return $productOfferIdsGroupedByIdServicePoint;
    }
}
