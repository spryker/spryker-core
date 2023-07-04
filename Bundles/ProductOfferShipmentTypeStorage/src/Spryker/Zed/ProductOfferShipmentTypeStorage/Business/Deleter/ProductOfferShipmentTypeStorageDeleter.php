<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Deleter;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferExtractorInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface;

class ProductOfferShipmentTypeStorageDeleter implements ProductOfferShipmentTypeStorageDeleterInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface
     */
    protected ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferExtractorInterface
     */
    protected ProductOfferExtractorInterface $productOfferExtractor;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeInterface
     */
    protected ProductOfferShipmentTypeStorageToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Persistence\ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Business\Extractor\ProductOfferExtractorInterface $productOfferExtractor
     * @param \Spryker\Zed\ProductOfferShipmentTypeStorage\Dependency\Facade\ProductOfferShipmentTypeStorageToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(
        ProductOfferShipmentTypeStorageEntityManagerInterface $productOfferShipmentTypeStorageEntityManager,
        ProductOfferExtractorInterface $productOfferExtractor,
        ProductOfferShipmentTypeStorageToProductOfferFacadeInterface $productOfferFacade
    ) {
        $this->productOfferShipmentTypeStorageEntityManager = $productOfferShipmentTypeStorageEntityManager;
        $this->productOfferExtractor = $productOfferExtractor;
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param list<int> $productOfferIds
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypeStoragesByProductOfferIds(array $productOfferIds, ?string $storeName = null): void
    {
        $productOfferCollectionTransfer = $this->getProductOfferCollectionTransfer($productOfferIds);
        $productOfferReferences = $this->productOfferExtractor->extractProductOfferReferencesFromProductOfferTransfers(
            $productOfferCollectionTransfer->getProductOffers(),
        );

        $this->deleteProductOfferShipmentTypeStoragesByProductOfferReferences($productOfferReferences, $storeName);
    }

    /**
     * @param list<string> $productOfferReferences
     * @param string|null $storeName
     *
     * @return void
     */
    public function deleteProductOfferShipmentTypeStoragesByProductOfferReferences(array $productOfferReferences, ?string $storeName = null): void
    {
        $this->productOfferShipmentTypeStorageEntityManager->deleteProductOfferShipmentTypeStorages($productOfferReferences, $storeName);
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
