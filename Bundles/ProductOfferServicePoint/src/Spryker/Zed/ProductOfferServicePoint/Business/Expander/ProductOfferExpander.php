<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Indexer\ServiceIndexerInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface;

class ProductOfferExpander implements ProductOfferExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface
     */
    protected ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface
     */
    protected ServiceReaderInterface $serviceReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface
     */
    protected ProductOfferServiceExtractorInterface $productOfferServiceExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Indexer\ServiceIndexerInterface
     */
    protected ServiceIndexerInterface $serviceIndexer;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface $serviceReader
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface $productOfferServiceExtractor
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface $productOfferReader
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Indexer\ServiceIndexerInterface $serviceIndexer
     */
    public function __construct(
        ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository,
        ServiceReaderInterface $serviceReader,
        ProductOfferServiceExtractorInterface $productOfferServiceExtractor,
        ProductOfferExtractorInterface $productOfferExtractor,
        ProductOfferReaderInterface $productOfferReader,
        ServiceIndexerInterface $serviceIndexer
    ) {
        $this->productOfferServicePointRepository = $productOfferServicePointRepository;
        $this->serviceReader = $serviceReader;
        $this->productOfferServiceExtractor = $productOfferServiceExtractor;
        $this->productOfferExtractor = $productOfferExtractor;
        $this->productOfferReader = $productOfferReader;
        $this->serviceIndexer = $serviceIndexer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function expandProductOfferCollectionWithServices(ProductOfferCollectionTransfer $productOfferCollectionTransfer): ProductOfferCollectionTransfer
    {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferCollectionTransfer->getProductOffers();

        $productOfferServiceConditionsTransfer = (new ProductOfferServiceConditionsTransfer())
            ->setProductOfferIds(
                $this->productOfferExtractor->extractProductOfferIdsFromProductOfferTransfers($productOfferTransfers),
            )
            ->setGroupByIdProductOffer(true);
        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->setProductOfferServiceConditions($productOfferServiceConditionsTransfer);

        $productOfferServiceCollectionTransfer = $this->productOfferServicePointRepository->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
        if (!count($productOfferServiceCollectionTransfer->getProductOfferServices())) {
            return $productOfferCollectionTransfer;
        }

        $serviceTransfersIndexedByIdProductOffer = $this->getServiceTransfersIndexedByIdProductOffer($productOfferServiceCollectionTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if (!isset($serviceTransfersIndexedByIdProductOffer[$productOfferTransfer->getIdProductOfferOrFail()])) {
                continue;
            }

            $productOfferTransfer->setServices(
                new ArrayObject($serviceTransfersIndexedByIdProductOffer[$productOfferTransfer->getIdProductOfferOrFail()]),
            );
        }

        return $productOfferCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer
     */
    public function expandProductOfferServiceCollectionRequestServicesWithServicePoints(
        ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
    ): ProductOfferServiceCollectionRequestTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferServiceCollectionRequestTransfer->getProductOffers();

        $serviceUuids = $this->productOfferExtractor->extractServiceUuidsFromProductOfferTransfers($productOfferTransfers);
        $serviceCollectionTransfer = $this->serviceReader->getServiceCollectionByServiceUuids($serviceUuids);
        $serviceTransfersIndexedByServiceUuid = $this->getServiceTransfersIndexedByServiceUuid($serviceCollectionTransfer);

        foreach ($productOfferTransfers as $productOfferTransfer) {
            $this->expandProductOfferServicesWithServicePoints($productOfferTransfer, $serviceTransfersIndexedByServiceUuid);
        }

        return $productOfferServiceCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer
     */
    public function expandProductOfferServiceCollectionRequestWithProductOffersIds(
        ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
    ): ProductOfferServiceCollectionRequestTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferServiceCollectionRequestTransfer->getProductOffers();

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

        return $productOfferServiceCollectionRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     * @param array<string, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfersIndexedByServiceUuid
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function expandProductOfferServicesWithServicePoints(
        ProductOfferTransfer $productOfferTransfer,
        array $serviceTransfersIndexedByServiceUuid
    ): ProductOfferTransfer {
        foreach ($productOfferTransfer->getServices() as $serviceTransfer) {
            if (!isset($serviceTransfersIndexedByServiceUuid[$serviceTransfer->getUuidOrFail()])) {
                continue;
            }

            $serviceTransfer->setServicePoint($serviceTransfersIndexedByServiceUuid[$serviceTransfer->getUuidOrFail()]->getServicePointOrFail());
            $serviceTransfer->setIdService($serviceTransfersIndexedByServiceUuid[$serviceTransfer->getUuidOrFail()]->getIdServiceOrFail());
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return array<int, list<\Generated\Shared\Transfer\ServiceTransfer>>
     */
    protected function getServiceTransfersIndexedByIdProductOffer(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): array {
        $serviceCollectionTransfer = $this->serviceReader->getServiceCollectionByServiceIds(
            $this->productOfferServiceExtractor->extractServiceIdsFromProductOfferServiceCollectionTransfer($productOfferServiceCollectionTransfer),
        );
        $serviceTransfersIndexedByIdService = $this->serviceIndexer->getServiceTransfersIndexedByIdService($serviceCollectionTransfer);
        $serviceTransfersIndexedByIdProductOffer = [];

        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $idProductOffer = $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();

            foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
                $serviceTransfersIndexedByIdProductOffer[$idProductOffer][]
                    = $serviceTransfersIndexedByIdService[$serviceTransfer->getIdServiceOrFail()];
            }
        }

        return $serviceTransfersIndexedByIdProductOffer;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionTransfer $serviceCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServiceTransfer>
     */
    protected function getServiceTransfersIndexedByServiceUuid(ServiceCollectionTransfer $serviceCollectionTransfer): array
    {
        $serviceTransfersIndexedByServiceUuid = [];
        foreach ($serviceCollectionTransfer->getServices() as $serviceTransfer) {
            $serviceTransfersIndexedByServiceUuid[$serviceTransfer->getUuidOrFail()] = $serviceTransfer;
        }

        return $serviceTransfersIndexedByServiceUuid;
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
