<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointStorage\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServiceStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ServicePointStorageCollectionTransfer;
use Spryker\Client\ProductOfferServicePointStorage\Extractor\ProductOfferServiceStorageExtractorInterface;
use Spryker\Client\ProductOfferServicePointStorage\Extractor\ProductOfferStorageExtractorInterface;
use Spryker\Client\ProductOfferServicePointStorage\Reader\ProductOfferServiceStorageReaderInterface;
use Spryker\Client\ProductOfferServicePointStorage\Reader\ServicePointStorageReaderInterface;

class ProductOfferStorageServiceExpander implements ProductOfferStorageServiceExpanderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Extractor\ProductOfferStorageExtractorInterface
     */
    protected ProductOfferStorageExtractorInterface $productOfferStorageExtractor;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Reader\ProductOfferServiceStorageReaderInterface
     */
    protected ProductOfferServiceStorageReaderInterface $productOfferServiceStorageReader;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Extractor\ProductOfferServiceStorageExtractorInterface
     */
    protected ProductOfferServiceStorageExtractorInterface $productOfferServiceStorageExtractor;

    /**
     * @var \Spryker\Client\ProductOfferServicePointStorage\Reader\ServicePointStorageReaderInterface
     */
    protected ServicePointStorageReaderInterface $servicePointStorageReader;

    /**
     * @param \Spryker\Client\ProductOfferServicePointStorage\Extractor\ProductOfferStorageExtractorInterface $productOfferStorageExtractor
     * @param \Spryker\Client\ProductOfferServicePointStorage\Reader\ProductOfferServiceStorageReaderInterface $productOfferServiceStorageReader
     * @param \Spryker\Client\ProductOfferServicePointStorage\Extractor\ProductOfferServiceStorageExtractorInterface $productOfferServiceStorageExtractor
     * @param \Spryker\Client\ProductOfferServicePointStorage\Reader\ServicePointStorageReaderInterface $servicePointStorageReader
     */
    public function __construct(
        ProductOfferStorageExtractorInterface $productOfferStorageExtractor,
        ProductOfferServiceStorageReaderInterface $productOfferServiceStorageReader,
        ProductOfferServiceStorageExtractorInterface $productOfferServiceStorageExtractor,
        ServicePointStorageReaderInterface $servicePointStorageReader
    ) {
        $this->productOfferStorageExtractor = $productOfferStorageExtractor;
        $this->productOfferServiceStorageReader = $productOfferServiceStorageReader;
        $this->productOfferServiceStorageExtractor = $productOfferServiceStorageExtractor;
        $this->servicePointStorageReader = $servicePointStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    public function expandProductOfferStorageCollection(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): ProductOfferStorageCollectionTransfer {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers */
        $productOfferStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffers();

        $productOfferReferences = $this->productOfferStorageExtractor->extractProductOfferReferencesFromProductOfferStorageTransfers(
            $productOfferStorageTransfers,
        );

        $productOfferServiceStorageCollectionTransfer = $this->productOfferServiceStorageReader->getProductOfferServiceStorageCollectionByProductOfferReferences(
            $productOfferReferences,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServiceStorageTransfer> $productOfferServiceStorageTransfers */
        $productOfferServiceStorageTransfers = $productOfferServiceStorageCollectionTransfer->getProductOfferServices();

        $servicePointUuids = $this->productOfferServiceStorageExtractor->extractServicePointUuidsFromProductOfferServiceStorageTransfers(
            $productOfferServiceStorageTransfers,
        );

        $servicePointStorageCollectionTransfer = $this->servicePointStorageReader->getServicePointStorageCollectionByServicePointUuids($servicePointUuids);
        $serviceStorageTransfersGroupedByProductOfferReference = $this->getServiceStorageTransfersGroupedByProductOfferReference(
            $productOfferServiceStorageCollectionTransfer,
            $servicePointStorageCollectionTransfer,
        );

        foreach ($productOfferStorageCollectionTransfer->getProductOffers() as $productOfferStorageTransfer) {
            $productOfferReference = $productOfferStorageTransfer->getProductOfferReferenceOrFail();
            if (!isset($serviceStorageTransfersGroupedByProductOfferReference[$productOfferReference])) {
                continue;
            }

            $productOfferStorageTransfer->setServices(new ArrayObject($serviceStorageTransfersGroupedByProductOfferReference[$productOfferReference]));
        }

        return $productOfferStorageCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceStorageCollectionTransfer $productOfferServiceStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ServiceStorageTransfer>>
     */
    protected function getServiceStorageTransfersGroupedByProductOfferReference(
        ProductOfferServiceStorageCollectionTransfer $productOfferServiceStorageCollectionTransfer,
        ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
    ): array {
        $serviceStorageTransfersGroupedByProductOfferReference = [];
        $serviceTransfersIndexedByUuid = $this->getServiceTransfersIndexedByUuid($servicePointStorageCollectionTransfer);

        foreach ($productOfferServiceStorageCollectionTransfer->getProductOfferServices() as $productOfferServiceStorageTransfer) {
            $productOfferReference = $productOfferServiceStorageTransfer->getProductOfferReferenceOrFail();

            foreach ($productOfferServiceStorageTransfer->getServiceUuids() as $serviceUuid) {
                if (!isset($serviceTransfersIndexedByUuid[$serviceUuid])) {
                    continue;
                }

                $serviceStorageTransfersGroupedByProductOfferReference[$productOfferReference][] = $serviceTransfersIndexedByUuid[$serviceUuid];
            }
        }

        return $serviceStorageTransfersGroupedByProductOfferReference;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ServiceStorageTransfer>
     */
    protected function getServiceTransfersIndexedByUuid(ServicePointStorageCollectionTransfer $servicePointStorageCollectionTransfer): array
    {
        $serviceTransfersIndexedByUuid = [];

        foreach ($servicePointStorageCollectionTransfer->getServicePointStorages() as $servicePointStorageTransfer) {
            foreach ($servicePointStorageTransfer->getServices() as $serviceStorageTransfer) {
                $serviceTransfersIndexedByUuid[$serviceStorageTransfer->getUuidOrFail()] = $serviceStorageTransfer;
            }
        }

        return $serviceTransfersIndexedByUuid;
    }
}
