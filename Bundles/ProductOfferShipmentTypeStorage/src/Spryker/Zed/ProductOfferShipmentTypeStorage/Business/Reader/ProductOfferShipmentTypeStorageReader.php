<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageRepositoryInterface;

class ProductOfferShipmentTypeStorageReader implements ProductOfferShipmentTypeStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageRepositoryInterface
     */
    protected ProductOfferShipmentTypeStorageRepositoryInterface $productOfferShipmentTypeStorageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageRepositoryInterface $productOfferShipmentTypeStorageRepository
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(
        ProductOfferShipmentTypeStorageRepositoryInterface $productOfferShipmentTypeStorageRepository,
        ProductOfferExtractorInterface $productOfferExtractor,
        ProductOfferShipmentTypeStorageToProductOfferFacadeInterface $productOfferFacade
    ) {
        $this->productOfferShipmentTypeStorageRepository = $productOfferShipmentTypeStorageRepository;
        $this->productOfferExtractor = $productOfferExtractor;
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
        FilterTransfer $filterTransfer,
        array $productOfferIds = []
    ): array {
        $productOfferCollectionTransfer = $this->getProductOfferCollectionTransfer($productOfferIds);
        $productOfferReferences = $this->productOfferExtractor->extractProductOfferReferencesFromProductOfferTransfers(
            $productOfferCollectionTransfer->getProductOffers(),
        );

        return $this->productOfferShipmentTypeStorageRepository
            ->getProductOfferShipmentTypeStorageSynchronizationDataTransfers(
                $filterTransfer,
                $productOfferReferences,
            );
    }

    /**
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    protected function getProductOfferCollectionTransfer(array $productOfferIds): ProductOfferCollectionTransfer
    {
        $productOfferConditionsTransfer = (new ProductOfferConditionsTransfer())->setProductOfferIds($productOfferIds);
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())->setProductOfferConditions($productOfferConditionsTransfer);

        return $this->productOfferFacade->getProductOfferCollection($productOfferCriteriaTransfer);
    }
}
