<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface;

class ProductOfferServicePointAvailabilityReader implements ProductOfferServicePointAvailabilityReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface
     */
    protected ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface $productOfferServicePointAvailabilityStorageClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface $storeClient
     */
    protected ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface $productOfferServicePointAvailabilityStorageClient
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface $storeClient
     */
    public function __construct(
        ProductOfferServicePointAvailabilityCalculatorStorageToProductOfferServicePointAvailabilityStorageClientInterface $productOfferServicePointAvailabilityStorageClient,
        ProductOfferServicePointAvailabilityCalculatorStorageToStoreClientInterface $storeClient
    ) {
        $this->productOfferServicePointAvailabilityStorageClient = $productOfferServicePointAvailabilityStorageClient;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer
     */
    public function getProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCollectionTransfer {
        $productOfferServicePointAvailabilityConditions = $productOfferServicePointAvailabilityCriteriaTransfer->getProductOfferServicePointAvailabilityConditionsOrFail();

        if (!$productOfferServicePointAvailabilityConditions->getStoreName()) {
            $productOfferServicePointAvailabilityConditions->setStoreName(
                $this->storeClient->getCurrentStore()->getNameOrFail(),
            );
        }

        $productOfferServicePointAvailabilityCriteriaTransfer = $this->createProductOfferServicePointAvailabilityCriteriaTransferForProductsWithProductOfferReferences(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );

        return $this->productOfferServicePointAvailabilityStorageClient->getProductOfferServicePointAvailabilityCollection(
            $productOfferServicePointAvailabilityCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $originalProductOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer
     */
    protected function createProductOfferServicePointAvailabilityCriteriaTransferForProductsWithProductOfferReferences(
        ProductOfferServicePointAvailabilityCriteriaTransfer $originalProductOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCriteriaTransfer {
        $originalProductOfferServicePointAvailabilityConditionsTransfer = $originalProductOfferServicePointAvailabilityCriteriaTransfer->getProductOfferServicePointAvailabilityConditionsOrFail();

        $productOfferServicePointAvailabilityConditionsTransfer = (new ProductOfferServicePointAvailabilityConditionsTransfer())
            ->fromArray($originalProductOfferServicePointAvailabilityConditionsTransfer->toArray())
            ->setProductOfferServicePointAvailabilityRequestItems(new ArrayObject());

        foreach ($originalProductOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems() as $productOfferServicePointAvailabilityRequestItemTransfer) {
            $productOfferServicePointAvailabilityConditionsTransfer->addProductOfferServicePointAvailabilityRequestItem(
                $productOfferServicePointAvailabilityRequestItemTransfer,
            );
        }

        return (new ProductOfferServicePointAvailabilityCriteriaTransfer())->setProductOfferServicePointAvailabilityConditions($productOfferServicePointAvailabilityConditionsTransfer);
    }
}
