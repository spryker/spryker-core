<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\Filter;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToMerchantFacadeInterface;

class MerchantProductOfferServiceStorageFilter implements MerchantProductOfferServiceStorageFilterInterface
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
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    public function filterProductOfferServices(array $productOfferServicesTransfers): array
    {
        if (!$productOfferServicesTransfers) {
            return $productOfferServicesTransfers;
        }

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setIsActive(true)
            ->setStatus(static::MERCHANT_STATUS_APPROVED)
            ->setMerchantReferences($this->extractMerchantReferencesFromProductOfferServicesTransfers($productOfferServicesTransfers));

        $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaTransfer);
        $activeMerchantReferences = $this->extractMerchantReferencesFromMerchantCollectionTransfer($merchantCollectionTransfer);

        return $this->filterOutProductOfferServicesWithInactiveMerchants($productOfferServicesTransfers, $activeMerchantReferences);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     *
     * @return list<string>
     */
    protected function extractMerchantReferencesFromProductOfferServicesTransfers(array $productOfferServicesTransfers): array
    {
        $merchantReferences = [];
        foreach ($productOfferServicesTransfers as $productOfferServicesTransfer) {
            if (!$productOfferServicesTransfer->getProductOfferOrFail()->getMerchantReference()) {
                continue;
            }

            $merchantReferences[] = $productOfferServicesTransfer->getProductOfferOrFail()->getMerchantReferenceOrFail();
        }

        return $merchantReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCollectionTransfer $merchantCollectionTransfer
     *
     * @return list<string>
     */
    protected function extractMerchantReferencesFromMerchantCollectionTransfer(MerchantCollectionTransfer $merchantCollectionTransfer): array
    {
        $merchantReferences = [];
        foreach ($merchantCollectionTransfer->getMerchants() as $merchantTransfer) {
            $merchantReferences[] = $merchantTransfer->getMerchantReferenceOrFail();
        }

        return $merchantReferences;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers
     * @param list<string> $activeMerchantReferences
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>
     */
    protected function filterOutProductOfferServicesWithInactiveMerchants(
        array $productOfferServicesTransfers,
        array $activeMerchantReferences
    ): array {
        $activeProductOfferServicesTransfers = [];
        foreach ($productOfferServicesTransfers as $productOfferServicesTransfer) {
            if (
                $productOfferServicesTransfer->getProductOfferOrFail()->getMerchantReference()
                && in_array($productOfferServicesTransfer->getProductOfferOrFail()->getMerchantReferenceOrFail(), $activeMerchantReferences, true)
            ) {
                $activeProductOfferServicesTransfers[] = $productOfferServicesTransfer;
            }
        }

        return $activeProductOfferServicesTransfers;
    }
}
