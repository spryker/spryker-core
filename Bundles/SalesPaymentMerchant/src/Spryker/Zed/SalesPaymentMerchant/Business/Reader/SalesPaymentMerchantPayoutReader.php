<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchant\Business\Reader;

use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutConditionsTransfer;
use Generated\Shared\Transfer\SalesPaymentMerchantPayoutCriteriaTransfer;
use Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface;
use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantConfig;

class SalesPaymentMerchantPayoutReader implements SalesPaymentMerchantPayoutReaderInterface
{
    /**
     * @var \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface
     */
    protected SalesPaymentMerchantRepositoryInterface $salesPaymentMerchantRepository;

    /**
     * @param \Spryker\Zed\SalesPaymentMerchant\Persistence\SalesPaymentMerchantRepositoryInterface $salesPaymentMerchantRepository
     */
    public function __construct(SalesPaymentMerchantRepositoryInterface $salesPaymentMerchantRepository)
    {
        $this->salesPaymentMerchantRepository = $salesPaymentMerchantRepository;
    }

    /**
     * @param string $orderReference
     * @param list<string> $merchantReferences
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutCollectionByOrderReferenceAndMerchants(
        string $orderReference,
        array $merchantReferences
    ): SalesPaymentMerchantPayoutCollectionTransfer {
        $salesPaymentMerchantPayoutConditionsTransfer = (new SalesPaymentMerchantPayoutConditionsTransfer())
            ->addOrderReference($orderReference)
            ->setMerchantReferences($merchantReferences);

        $salesPaymentMerchantPayoutCriteriaTransfer = $this->createPaymentMerchantPayoutCriteriaTransfer($salesPaymentMerchantPayoutConditionsTransfer);

        return $this->salesPaymentMerchantRepository->getSalesPaymentMerchantPayoutCollection($salesPaymentMerchantPayoutCriteriaTransfer);
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     * @param bool $isSuccessful
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer
     */
    public function getSalesPaymentMerchantPayoutCollectionByMerchantAndOrderReference(
        string $orderReference,
        string $merchantReference,
        bool $isSuccessful
    ): SalesPaymentMerchantPayoutCollectionTransfer {
        $salesPaymentMerchantPayoutConditionsTransfer = (new SalesPaymentMerchantPayoutConditionsTransfer())
            ->addOrderReference($orderReference)
            ->addMerchantReference($merchantReference)
            ->setIsSuccessful($isSuccessful);

        $salesPaymentMerchantPayoutCriteriaTransfer = $this->createPaymentMerchantPayoutCriteriaTransfer($salesPaymentMerchantPayoutConditionsTransfer);

        return $this->salesPaymentMerchantRepository->getSalesPaymentMerchantPayoutCollection($salesPaymentMerchantPayoutCriteriaTransfer);
    }

    /**
     * @param string $orderReference
     * @param list<string> $itemReferences
     *
     * @return array<string, \Generated\Shared\Transfer\SalesPaymentMerchantPayoutTransfer>
     */
    public function getSalesPaymentMerchantPayoutMapByItemReferences(
        string $orderReference,
        array $itemReferences
    ): array {
        $salesPaymentMerchantPayoutConditionsTransfer = (new SalesPaymentMerchantPayoutConditionsTransfer())
            ->addOrderReference($orderReference)
            ->setItemReferences($itemReferences)
            ->setIsSuccessful(true);

        $salesPaymentMerchantPayoutCriteriaTransfer = $this->createPaymentMerchantPayoutCriteriaTransfer($salesPaymentMerchantPayoutConditionsTransfer);
        $salesPaymentMerchantPayoutCollectionTransfer = $this->salesPaymentMerchantRepository->getSalesPaymentMerchantPayoutCollection($salesPaymentMerchantPayoutCriteriaTransfer);

        $salesPaymentMerchantPayoutMapByItemReferences = [];
        foreach ($salesPaymentMerchantPayoutCollectionTransfer->getSalesPaymentMerchantPayouts() as $salesPaymentMerchantPayoutTransfer) {
            $salesPaymentMerchantPayoutMapByItemReferences[$salesPaymentMerchantPayoutTransfer->getItemReferencesOrFail()] = $salesPaymentMerchantPayoutTransfer;
        }

        return $salesPaymentMerchantPayoutMapByItemReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
     *
     * @return array<string, string>
     */
    public function getSalesPaymentMerchantPayoutTransferTransferIdMapIndexedByItemReference(
        SalesPaymentMerchantPayoutCollectionTransfer $salesPaymentMerchantPayoutCollectionTransfer
    ): array {
        $salesPaymentMerchantPayoutTransferTransferIdMapIndexedByItemReference = [];
        foreach ($salesPaymentMerchantPayoutCollectionTransfer->getSalesPaymentMerchantPayouts() as $salesPaymentMerchantPayoutTransfer) {
            $transferId = $salesPaymentMerchantPayoutTransfer->getTransferIdOrFail();
            $itemReferences = explode(
                SalesPaymentMerchantConfig::ITEM_REFERENCE_SEPARATOR,
                $salesPaymentMerchantPayoutTransfer->getItemReferencesOrFail(),
            );

            foreach ($itemReferences as $itemReference) {
                $salesPaymentMerchantPayoutTransferTransferIdMapIndexedByItemReference[$itemReference] = $transferId;
            }
        }

        return $salesPaymentMerchantPayoutTransferTransferIdMapIndexedByItemReference;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesPaymentMerchantPayoutConditionsTransfer $salesPaymentMerchantPayoutConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMerchantPayoutCriteriaTransfer
     */
    public function createPaymentMerchantPayoutCriteriaTransfer(
        SalesPaymentMerchantPayoutConditionsTransfer $salesPaymentMerchantPayoutConditionsTransfer
    ): SalesPaymentMerchantPayoutCriteriaTransfer {
        $salesPaymentMerchantPayoutCriteriaTransfer = new SalesPaymentMerchantPayoutCriteriaTransfer();
        $salesPaymentMerchantPayoutCriteriaTransfer->setSalesPaymentMerchantPayoutConditions($salesPaymentMerchantPayoutConditionsTransfer);

        return $salesPaymentMerchantPayoutCriteriaTransfer;
    }
}
