<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface;

class ProductOfferServicePointAvailabilityReader implements ProductOfferServicePointAvailabilityReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface
     */
    protected ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface $productOfferServicePointAvailabilityClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface $storeClient
     */
    protected ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface $storeClient;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface $productOfferServicePointAvailabilityClient
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityCalculator\Dependency\Client\ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface $storeClient
     */
    public function __construct(
        ProductOfferServicePointAvailabilityCalculatorToProductOfferServicePointAvailabilityClientInterface $productOfferServicePointAvailabilityClient,
        ProductOfferServicePointAvailabilityCalculatorToStoreClientInterface $storeClient
    ) {
        $this->productOfferServicePointAvailabilityClient = $productOfferServicePointAvailabilityClient;
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

        return $this->productOfferServicePointAvailabilityClient->getProductOfferServicePointAvailabilityCollection(
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
            if (!$productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReference()) {
                continue;
            }

            $productOfferServicePointAvailabilityConditionsTransfer->addProductOfferServicePointAvailabilityRequestItem(
                $productOfferServicePointAvailabilityRequestItemTransfer,
            );
        }

        return (new ProductOfferServicePointAvailabilityCriteriaTransfer())->setProductOfferServicePointAvailabilityConditions($productOfferServicePointAvailabilityConditionsTransfer);
    }
}
