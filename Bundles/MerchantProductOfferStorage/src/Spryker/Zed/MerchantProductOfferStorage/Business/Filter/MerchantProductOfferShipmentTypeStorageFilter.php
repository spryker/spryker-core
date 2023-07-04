<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToMerchantFacadeInterface;

class MerchantProductOfferShipmentTypeStorageFilter implements MerchantProductOfferShipmentTypeStorageFilterInterface
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToMerchantFacadeInterface
     */
    protected MerchantProductOfferStorageToMerchantFacadeInterface $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantProductOfferStorageToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function filterProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $merchantReferences = $this->extractMerchantReferencesFromProductOfferShipmentTypeCollectionTransfer(
            $productOfferShipmentTypeCollectionTransfer,
        );
        $merchantCollectionTransfer = $this->getActiveMerchants($merchantReferences);
        $activeMerchantReferences = $this->extractMerchantReferenceFromMerchantCollectionTransfer($merchantCollectionTransfer);

        return $this->filterOutProductOfferShipmentTypesWithInactiveMerchants(
            $productOfferShipmentTypeCollectionTransfer,
            $activeMerchantReferences,
        );
    }

    /**
     * @param list<string> $merchantReferences
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    protected function getActiveMerchants(array $merchantReferences): MerchantCollectionTransfer
    {
        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setMerchantReferences($merchantReferences)
            ->setIsActive(true)
            ->setStatus(static::MERCHANT_STATUS_APPROVED);

        return $this->merchantFacade->get($merchantCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractMerchantReferencesFromProductOfferShipmentTypeCollectionTransfer(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
    ): array {
        $merchantReferences = [];
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $merchantReferences[] = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getMerchantReference();
        }

        return array_filter($merchantReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractMerchantReferenceFromMerchantCollectionTransfer(MerchantCollectionTransfer $merchantCollectionTransfer): array
    {
        $merchantReferences = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantReferences[] = $merchantTransfer->getMerchantReferenceOrFail();
        }

        return $merchantReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param list<string> $activeMerchantReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    protected function filterOutProductOfferShipmentTypesWithInactiveMerchants(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        array $activeMerchantReferences
    ): ProductOfferShipmentTypeCollectionTransfer {
        $filteredProductOfferShipmentTypeTransfers = new ArrayObject();
        foreach ($productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes() as $productOfferShipmentTypeTransfer) {
            $merchantReference = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getMerchantReference();
            if ($merchantReference && !in_array($merchantReference, $activeMerchantReferences, true)) {
                continue;
            }

            $filteredProductOfferShipmentTypeTransfers->append($productOfferShipmentTypeTransfer);
        }

        return $productOfferShipmentTypeCollectionTransfer->setProductOfferShipmentTypes($filteredProductOfferShipmentTypeTransfers);
    }
}
