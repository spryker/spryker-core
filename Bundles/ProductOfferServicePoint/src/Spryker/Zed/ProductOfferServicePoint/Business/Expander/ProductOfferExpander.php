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
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface;
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
     * @param \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface $serviceReader
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface $productOfferServiceExtractor
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     */
    public function __construct(
        ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository,
        ServiceReaderInterface $serviceReader,
        ProductOfferServiceExtractorInterface $productOfferServiceExtractor,
        ProductOfferExtractorInterface $productOfferExtractor
    ) {
        $this->productOfferServicePointRepository = $productOfferServicePointRepository;
        $this->serviceReader = $serviceReader;
        $this->productOfferServiceExtractor = $productOfferServiceExtractor;
        $this->productOfferExtractor = $productOfferExtractor;
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
        $productOfferServiceCollectionTransfer = $this->productOfferServicePointRepository->getProductOfferServiceCollectionByProductOfferReferences(
            $this->productOfferExtractor->extractProductOfferReferencesFromProductOfferTransfers($productOfferTransfers),
        );
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServiceTransfer> $productOfferServiceTransfers */
        $productOfferServiceTransfers = $productOfferServiceCollectionTransfer->getProductOfferServices();

        if (!$productOfferServiceTransfers->count()) {
            return $productOfferCollectionTransfer;
        }

        $serviceTransfersIndexedByProductOfferReference = $this->getServiceTransfersIndexedByProductOfferReference($productOfferServiceCollectionTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if (!isset($serviceTransfersIndexedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()])) {
                continue;
            }

            $productOfferTransfer->setServices(
                new ArrayObject($serviceTransfersIndexedByProductOfferReference[$productOfferTransfer->getProductOfferReferenceOrFail()]),
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
        }

        return $productOfferTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ServiceTransfer>>
     */
    protected function getServiceTransfersIndexedByProductOfferReference(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): array {
        $serviceCollectionTransfer = $this->serviceReader->getServiceCollectionByServiceUuids(
            $this->productOfferServiceExtractor->extractServiceUuidsFromProductOfferServiceCollectionTransfer($productOfferServiceCollectionTransfer),
        );
        $serviceTransfersIndexedByServiceUuid = $this->getServiceTransfersIndexedByServiceUuid($serviceCollectionTransfer);
        $serviceTransfersIndexedByProductOfferReference = [];

        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServiceTransfer) {
            $serviceTransfersIndexedByProductOfferReference[$productOfferServiceTransfer->getProductOfferReferenceOrFail()][]
                = $serviceTransfersIndexedByServiceUuid[$productOfferServiceTransfer->getServiceUuidOrFail()];
        }

        return $serviceTransfersIndexedByProductOfferReference;
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
}
