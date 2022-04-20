<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface;

class MerchantProductOfferStorageFilter implements MerchantProductOfferStorageFilterInterface
{
    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantStorage\Dependency\Facade\MerchantStorageToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(MerchantStorageToMerchantFacadeInterface $merchantFacade)
    {
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function filterProductOfferStorages(
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductOfferCollectionTransfer {
        $productOfferTransfers = $productOfferCollectionTransfer->getProductOffers();

        if ($productOfferTransfers->count() < 1) {
            return $productOfferCollectionTransfer;
        }

        $productOfferMerchantReferences = $this->getProductOfferMerchantReferences($productOfferTransfers);

        $merchantCriteriaTransfer = (new MerchantCriteriaTransfer())
            ->setIsActive(true)
            ->setStatus(static::MERCHANT_STATUS_APPROVED)
            ->setMerchantReferences($productOfferMerchantReferences);
        $merchantCollectionTransfer = $this->merchantFacade->get($merchantCriteriaTransfer);

        $merchantTransfers = $merchantCollectionTransfer->getMerchants();
        $activeMerchantReferences = $this->getMerchantReferences($merchantTransfers);

        $filteredProductOfferTransfers = $this->filterProductOfferStorageTransfersByActiveMerchantReferences(
            $productOfferTransfers,
            $activeMerchantReferences,
        );

        return $productOfferCollectionTransfer->setProductOffers($filteredProductOfferTransfers);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return array<string>
     */
    protected function getProductOfferMerchantReferences(ArrayObject $productOfferTransfers): array
    {
        $productOfferMerchantReferences = [];
        foreach ($productOfferTransfers as $productOfferTransfer) {
            /** @var string $productOfferMerchantReference */
            $productOfferMerchantReference = $productOfferTransfer->getMerchantReference();

            if (!$productOfferMerchantReference) {
                continue;
            }

            $productOfferMerchantReferences[] = $productOfferMerchantReference;
        }

        return $productOfferMerchantReferences;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return array<string>
     */
    protected function getMerchantReferences(ArrayObject $merchantTransfers): array
    {
        $merchantReferences = [];
        foreach ($merchantTransfers as $merchantTransfer) {
            $merchantReferences[] = $merchantTransfer->getMerchantReference();
        }

        return $merchantReferences;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     * @param array<string> $activeMerchantReferences
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function filterProductOfferStorageTransfersByActiveMerchantReferences(
        ArrayObject $productOfferTransfers,
        array $activeMerchantReferences
    ): ArrayObject {
        $productOfferTransferArray = $productOfferTransfers->getArrayCopy();
        foreach ($productOfferTransferArray as $key => $productOfferTransfer) {
            /** @var string $productOfferMerchantReference */
            $productOfferMerchantReference = $productOfferTransfer->getMerchantReference();

            if (
                !$productOfferMerchantReference
                || !in_array($productOfferMerchantReference, $activeMerchantReferences, true)
            ) {
                unset($productOfferTransferArray[$key]);
            }
        }

        return new ArrayObject($productOfferTransferArray);
    }
}
