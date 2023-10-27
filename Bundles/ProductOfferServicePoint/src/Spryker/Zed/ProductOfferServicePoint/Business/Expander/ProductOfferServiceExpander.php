<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicesTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Indexer\ServiceIndexerInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface;
use Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface;

class ProductOfferServiceExpander implements ProductOfferServiceExpanderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface
     */
    protected ProductOfferServiceExtractorInterface $productOfferServiceExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface
     */
    protected ServiceReaderInterface $serviceReader;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Indexer\ServiceIndexerInterface
     */
    protected ServiceIndexerInterface $serviceIndexer;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Extractor\ProductOfferServiceExtractorInterface $productOfferServiceExtractor
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ProductOfferReaderInterface $productOfferReader
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Reader\ServiceReaderInterface $serviceReader
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Indexer\ServiceIndexerInterface $serviceIndexer
     */
    public function __construct(
        ProductOfferServiceExtractorInterface $productOfferServiceExtractor,
        ProductOfferReaderInterface $productOfferReader,
        ServiceReaderInterface $serviceReader,
        ServiceIndexerInterface $serviceIndexer
    ) {
        $this->productOfferServiceExtractor = $productOfferServiceExtractor;
        $this->productOfferReader = $productOfferReader;
        $this->serviceReader = $serviceReader;
        $this->serviceIndexer = $serviceIndexer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function expandProductOfferServiceCollectionWithServicePoints(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): ProductOfferServiceCollectionTransfer {
        $serviceIds = $this->productOfferServiceExtractor->extractServiceIdsFromProductOfferServiceCollectionTransfer($productOfferServiceCollectionTransfer);
        $serviceCollectionTransfer = $this->serviceReader->getServiceCollectionByServiceConditions(
            (new ServiceConditionsTransfer())
                ->setServiceIds($serviceIds)
                ->setWithServicePointRelations(true),
        );
        $serviceTransfersIndexedByIdService = $this->serviceIndexer->getServiceTransfersIndexedByIdService($serviceCollectionTransfer);

        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $this->expandProductOfferServicesTransferWithServicePoints(
                $productOfferServicesTransfer,
                $serviceTransfersIndexedByIdService,
            );
        }

        return $productOfferServiceCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function expandProductOfferServiceCollectionWithProductOffersByIterableProductOfferServicesCriteria(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer,
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        $productOfferIds = $this->productOfferServiceExtractor->extractProductOfferIdsFromProductOfferServiceCollectionTransfer(
            $productOfferServiceCollectionTransfer,
        );
        $iterableProductOfferServicesCriteriaTransfer->getIterableProductOfferServicesConditionsOrFail()->setProductOfferIds($productOfferIds);

        $productOfferCollectionTransfer = $this->productOfferReader->getProductOfferCollectionByIterableProductOfferServicesCriteria($iterableProductOfferServicesCriteriaTransfer);
        $productOfferTransfersIndexedByIdProductOffer = $this->getProductOfferTransfersIndexedByIdProductOffer($productOfferCollectionTransfer);

        $productOfferServicesTransfers = [];
        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $idProductOffer = $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            if (!isset($productOfferTransfersIndexedByIdProductOffer[$idProductOffer])) {
                continue;
            }

            $productOfferServicesTransfer->setProductOffer(
                $productOfferTransfersIndexedByIdProductOffer[$idProductOffer],
            );

            $productOfferServicesTransfers[] = $productOfferServicesTransfer;
        }

        return $productOfferServiceCollectionTransfer->setProductOfferServices(new ArrayObject($productOfferServicesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function expandProductOfferServiceCollectionWithServicesByIterableProductOfferServicesCriteria(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer,
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        $serviceIds = $this->productOfferServiceExtractor->extractServiceIdsFromProductOfferServiceCollectionTransfer($productOfferServiceCollectionTransfer);
        $iterableProductOfferServicesCriteriaTransfer->getIterableProductOfferServicesConditionsOrFail()->setServiceIds($serviceIds);

        $serviceCollectionTransfer = $this->serviceReader->getServiceCollectionByIterableProductOfferServicesCriteria($iterableProductOfferServicesCriteriaTransfer);
        $serviceTransfersIndexedByIdService = $this->serviceIndexer->getServiceTransfersIndexedByIdService($serviceCollectionTransfer);

        $productOfferServicesTransfers = [];
        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $productOfferServicesTransfer = $this->filterOutMissingServiceTransfers($productOfferServicesTransfer, $serviceTransfersIndexedByIdService);
            if (count($productOfferServicesTransfer->getServices())) {
                $productOfferServicesTransfers[] = $productOfferServicesTransfer;
            }
        }

        return $productOfferServiceCollectionTransfer->setProductOfferServices(new ArrayObject($productOfferServicesTransfers));
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer
     * @param array<int, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfersIndexedByIdService
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicesTransfer
     */
    protected function expandProductOfferServicesTransferWithServicePoints(
        ProductOfferServicesTransfer $productOfferServicesTransfer,
        array $serviceTransfersIndexedByIdService
    ): ProductOfferServicesTransfer {
        foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
            $serviceTransfer->setServicePoint(
                $serviceTransfersIndexedByIdService[$serviceTransfer->getIdServiceOrFail()]->getServicePointOrFail(),
            );
        }

        return $productOfferServicesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function getProductOfferTransfersIndexedByIdProductOffer(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOfferTransfersIndexedByIdProductOffer = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferTransfersIndexedByIdProductOffer[$productOfferTransfer->getIdProductOfferOrFail()] = $productOfferTransfer;
        }

        return $productOfferTransfersIndexedByIdProductOffer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicesTransfer $productOfferServicesTransfer
     * @param array<int, \Generated\Shared\Transfer\ServiceTransfer> $serviceTransfersIndexedByIdService
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicesTransfer
     */
    protected function filterOutMissingServiceTransfers(
        ProductOfferServicesTransfer $productOfferServicesTransfer,
        array $serviceTransfersIndexedByIdService
    ): ProductOfferServicesTransfer {
        $serviceTransfers = [];
        foreach ($productOfferServicesTransfer->getServices() as $serviceTransfer) {
            if (!isset($serviceTransfersIndexedByIdService[$serviceTransfer->getIdServiceOrFail()])) {
                continue;
            }

            $serviceTransfers[] = $serviceTransfersIndexedByIdService[$serviceTransfer->getIdServiceOrFail()];
        }

        return $productOfferServicesTransfer->setServices(new ArrayObject($serviceTransfers));
    }
}
