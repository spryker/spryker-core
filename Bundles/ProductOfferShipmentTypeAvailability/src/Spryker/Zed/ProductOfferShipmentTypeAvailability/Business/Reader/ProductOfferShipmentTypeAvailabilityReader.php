<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Expander\SellableItemsResponseExpanderInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Extractor\SellableItemRequestExtractorInterface;
use Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Filter\SellableItemRequestFilterInterface;

class ProductOfferShipmentTypeAvailabilityReader implements ProductOfferShipmentTypeAvailabilityReaderInterface
{
    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Extractor\SellableItemRequestExtractorInterface
     */
    protected SellableItemRequestExtractorInterface $sellableItemRequestExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeReaderInterface
     */
    protected ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Expander\SellableItemsResponseExpanderInterface
     */
    protected SellableItemsResponseExpanderInterface $sellableItemsResponseExpander;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Filter\SellableItemRequestFilterInterface
     */
    protected SellableItemRequestFilterInterface $sellableItemRequestFilter;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferReaderInterface $productOfferReader
     * @param \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Extractor\SellableItemRequestExtractorInterface $sellableItemRequestExtractor
     * @param \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Reader\ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader
     * @param \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Expander\SellableItemsResponseExpanderInterface $sellableItemsResponseExpander
     * @param \Spryker\Zed\ProductOfferShipmentTypeAvailability\Business\Filter\SellableItemRequestFilterInterface $sellableItemRequestFilter
     */
    public function __construct(
        ProductOfferReaderInterface $productOfferReader,
        SellableItemRequestExtractorInterface $sellableItemRequestExtractor,
        ProductOfferShipmentTypeReaderInterface $productOfferShipmentTypeReader,
        SellableItemsResponseExpanderInterface $sellableItemsResponseExpander,
        SellableItemRequestFilterInterface $sellableItemRequestFilter
    ) {
        $this->productOfferReader = $productOfferReader;
        $this->sellableItemRequestExtractor = $sellableItemRequestExtractor;
        $this->productOfferShipmentTypeReader = $productOfferShipmentTypeReader;
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
        $sellableItemRequestTransfers = $this->sellableItemRequestFilter->filterSellableItemRequestTransfersWithProductOfferReferenceAndShipmentType(
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
        $productOfferShipmentTypeCollection = $this->productOfferShipmentTypeReader->getProductOfferShipmentTypeCollectionByProductOfferIds(
            array_values($productOfferIdsIndexedByProductOfferReference),
        );

        $shipmentTypeIdsGroupedByIdProductOffer = $this->getShipmentTypeIdsGroupedByIdProductOffer($productOfferShipmentTypeCollection);

        foreach ($sellableItemRequestTransfers as $sellableItemRequestTransfer) {
            $isSellableItemRequestValid = $this->isSellableItemRequestValid(
                $sellableItemRequestTransfer,
                $productOfferIdsIndexedByProductOfferReference,
                $shipmentTypeIdsGroupedByIdProductOffer,
            );

            if ($isSellableItemRequestValid) {
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
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return array<int, list<int>>
     */
    protected function getShipmentTypeIdsGroupedByIdProductOffer(ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer): array
    {
        $shipmentTypeIdsGroupedByIdProductOffer = [];

        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            foreach ($productOfferShipmentTypeTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeIdsGroupedByIdProductOffer[$productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail()][]
                    = $shipmentTypeTransfer->getIdShipmentTypeOrFail();
            }
        }

        return $shipmentTypeIdsGroupedByIdProductOffer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemRequestTransfer $sellableItemRequestTransfer
     * @param array<string, int> $productOfferIdsIndexedByProductOfferReference
     * @param array<int, list<int>> $shipmentTypeIdsGroupedByIdProductOffer
     *
     * @return bool
     */
    protected function isSellableItemRequestValid(
        mixed $sellableItemRequestTransfer,
        array $productOfferIdsIndexedByProductOfferReference,
        array $shipmentTypeIdsGroupedByIdProductOffer
    ): bool {
        $productAvailabilityCriteriaTransfer = $sellableItemRequestTransfer->getProductAvailabilityCriteriaOrFail();
        $shipmentTypeTransfer = $productAvailabilityCriteriaTransfer->getShipmentTypeOrFail();
        $idProductOffer = $productOfferIdsIndexedByProductOfferReference[$productAvailabilityCriteriaTransfer->getProductOfferReferenceOrFail()];

        /** @deprecated Exists for Backward Compatibility reasons only. */
        if (empty($shipmentTypeIdsGroupedByIdProductOffer[$idProductOffer]) && $shipmentTypeTransfer->getKey() === static::SHIPMENT_TYPE_DELIVERY) {
            return true;
        }

        $idShipmentType = $shipmentTypeTransfer->getIdShipmentTypeOrFail();

        return isset($shipmentTypeIdsGroupedByIdProductOffer[$idProductOffer])
            && in_array($idShipmentType, $shipmentTypeIdsGroupedByIdProductOffer[$idProductOffer], true);
    }
}
