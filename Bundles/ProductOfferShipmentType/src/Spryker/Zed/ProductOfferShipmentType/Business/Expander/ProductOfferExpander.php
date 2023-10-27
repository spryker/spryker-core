<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferShipmentTypeExtractorInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface;
use Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface
     */
    protected ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferShipmentTypeExtractorInterface
     */
    protected ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface
     */
    protected ShipmentTypeReaderInterface $shipmentTypeReader;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface
     */
    protected ShipmentTypeIndexerInterface $shipmentTypeIndexer;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Persistence\ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Extractor\ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Reader\ShipmentTypeReaderInterface $shipmentTypeReader
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Indexer\ShipmentTypeIndexerInterface $shipmentTypeIndexer
     */
    public function __construct(
        ProductOfferShipmentTypeRepositoryInterface $productOfferShipmentTypeRepository,
        ProductOfferExtractorInterface $productOfferExtractor,
        ProductOfferShipmentTypeExtractorInterface $productOfferShipmentTypeExtractor,
        ShipmentTypeReaderInterface $shipmentTypeReader,
        ShipmentTypeIndexerInterface $shipmentTypeIndexer
    ) {
        $this->productOfferShipmentTypeRepository = $productOfferShipmentTypeRepository;
        $this->productOfferExtractor = $productOfferExtractor;
        $this->productOfferShipmentTypeExtractor = $productOfferShipmentTypeExtractor;
        $this->shipmentTypeReader = $shipmentTypeReader;
        $this->shipmentTypeIndexer = $shipmentTypeIndexer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function expandProductOfferCollectionWithShipmentTypes(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferCollectionTransfer->getProductOffers();

        $productOfferShipmentTypeConditionsTransfer = (new ProductOfferShipmentTypeConditionsTransfer())
            ->setProductOfferIds(
                $this->productOfferExtractor->extractProductOfferIdsFromProductOfferTransfers($productOfferTransfers),
            );
        $productOfferShipmentTypeCriteriaTransfer = (new ProductOfferShipmentTypeCriteriaTransfer())
            ->setProductOfferShipmentTypeConditions($productOfferShipmentTypeConditionsTransfer);

        $productOfferShipmentTypeCollectionTransfer = $this->productOfferShipmentTypeRepository
            ->getProductOfferShipmentTypeCollection($productOfferShipmentTypeCriteriaTransfer);
        if (!count($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes())) {
            return $productOfferCollectionTransfer;
        }

        $shipmentTypeTransfersGroupedByIdProductOffer = $this->getShipmentTypeTransfersGroupedByIdProductOffer(
            $productOfferShipmentTypeCollectionTransfer,
        );

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $idProductOffer = $productOfferTransfer->getIdProductOfferOrFail();

            if (!isset($shipmentTypeTransfersGroupedByIdProductOffer[$idProductOffer])) {
                continue;
            }

            $productOfferTransfer->setShipmentTypes(
                new ArrayObject($shipmentTypeTransfersGroupedByIdProductOffer[$idProductOffer]),
            );
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\ShipmentTypeTransfer>>
     */
    protected function getShipmentTypeTransfersGroupedByIdProductOffer(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): array {
        $shipmentTypeCollectionTransfer = $this->shipmentTypeReader->getShipmentTypeCollectionByShipmentTypeIds(
            $this->productOfferShipmentTypeExtractor->extractShipmentTypeIdsFromProductOfferShipmentTypeCollection(
                $productOfferShipmentTypeCollectionTransfer,
            ),
        );
        $shipmentTypeTransfersIndexedByIdShipmentType = $this->shipmentTypeIndexer
            ->getShipmentTypeTransfersIndexedByIdShipmentType($shipmentTypeCollectionTransfer->getShipmentTypes());

        $shipmentTypeTransfersGroupedByIdProductOffer = [];
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $idProductOffer = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();

            foreach ($productOfferShipmentTypeTransfer->getShipmentTypes() as $shipmentTypeTransfer) {
                $shipmentTypeTransfersGroupedByIdProductOffer[$idProductOffer][]
                    = $shipmentTypeTransfersIndexedByIdShipmentType[$shipmentTypeTransfer->getIdShipmentTypeOrFail()];
            }
        }

        return $shipmentTypeTransfersGroupedByIdProductOffer;
    }
}
