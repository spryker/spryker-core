<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityCalculatorStorage\Calculator\Strategy;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;

class DefaultProductOfferServicePointAvailabilityCalculatorStrategy implements ProductOfferServicePointAvailabilityCalculatorStrategyInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilities(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): array {
        $productOfferServicePointAvailabilityRequestItemTransfers = $productOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems()->getArrayCopy();

        [
            $productOfferServicePointAvailabilityRequestItemTransfers,
            $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference,
        ] = $this->splitProductOfferServicePointAvailabilityRequestItemTransfersByProductOfferReferencePresence($productOfferServicePointAvailabilityRequestItemTransfers);

        $requestedProductOfferReferences = $this->extractProductOfferReferencesFromProductOfferServicePointAvailabilityRequestItemTransfers(
            $productOfferServicePointAvailabilityRequestItemTransfers,
        );

        $productOfferServicePointAvailabilityResponseItemTransfers = $this->filterOutProductOfferServicePointAvailabilityResponseItemTransfersWithIrrelevantProductOfferReference(
            $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems()->getArrayCopy(),
            $requestedProductOfferReferences,
        );

        $productOfferServicePointAvailabilityResponseItemTransfersMap = $this->getProductOfferServicePointAvailabilityResponseItemTransfersMap(
            $productOfferServicePointAvailabilityResponseItemTransfers,
        );

        $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = [];

        $servicePointUuids = $productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids();

        foreach ($productOfferServicePointAvailabilityRequestItemTransfers as $productOfferServicePointAvailabilityRequestItemTransfer) {
            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->calculateProductOfferAvailabilityAtServicePoints(
                $productOfferServicePointAvailabilityRequestItemTransfer,
                $servicePointUuids,
                $productOfferServicePointAvailabilityResponseItemTransfersMap,
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
            );
        }

        if ($productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference) {
            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->addAvailabilitiesForItemsWithoutProductOfferReference(
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
                $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference,
                $servicePointUuids,
            );
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer
     * @param list<string> $servicePointUuids
     * @param array<string, array<string, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersMap
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function calculateProductOfferAvailabilityAtServicePoints(
        ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer,
        array $servicePointUuids,
        array $productOfferServicePointAvailabilityResponseItemTransfersMap,
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
    ): array {
        foreach ($servicePointUuids as $servicePointUuid) {
            $productOfferReference = $productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReferenceOrFail();
            $productOfferServicePointAvailabilityResponseItemTransfer = $productOfferServicePointAvailabilityResponseItemTransfersMap[$servicePointUuid][$productOfferReference] ?? null;

            if (!$productOfferServicePointAvailabilityResponseItemTransfer) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] = $this->createUnavailableProductOfferServicePointAvailabilityResponseItem(
                    $productOfferServicePointAvailabilityRequestItemTransfer,
                    $servicePointUuid,
                );

                continue;
            }

            if ($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStockOrFail()) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] =
                    (clone $productOfferServicePointAvailabilityResponseItemTransfer)
                        ->setIdentifier($productOfferServicePointAvailabilityRequestItemTransfer->getIdentifierOrFail())
                        ->setIsAvailable(true);

                continue;
            }

            $availableQuantity = $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantityOrFail();
            $requestedQuantity = $productOfferServicePointAvailabilityRequestItemTransfer->getQuantityOrFail();

            if ($availableQuantity >= $requestedQuantity) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] =
                    (clone $productOfferServicePointAvailabilityResponseItemTransfer)
                        ->setIdentifier($productOfferServicePointAvailabilityRequestItemTransfer->getIdentifierOrFail())
                        ->setIsAvailable(true);

                $productOfferServicePointAvailabilityResponseItemTransfer->setAvailableQuantity(
                    $availableQuantity - $requestedQuantity,
                );

                continue;
            }

            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] =
                $productOfferServicePointAvailabilityResponseItemTransfer
                    ->setIdentifier($productOfferServicePointAvailabilityRequestItemTransfer->getIdentifierOrFail())
                    ->setIsAvailable(false);
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfers
     *
     * @return list<list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer>>
     */
    protected function splitProductOfferServicePointAvailabilityRequestItemTransfersByProductOfferReferencePresence(
        array $productOfferServicePointAvailabilityRequestItemTransfers
    ): array {
        $productOfferServicePointAvailabilityRequestItemTransfersWithProductOfferReference = [];
        $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference = [];

        foreach ($productOfferServicePointAvailabilityRequestItemTransfers as $productOfferServicePointAvailabilityRequestItemTransfer) {
            if ($productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReference()) {
                $productOfferServicePointAvailabilityRequestItemTransfersWithProductOfferReference[] = $productOfferServicePointAvailabilityRequestItemTransfer;

                continue;
            }

            $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference[] = $productOfferServicePointAvailabilityRequestItemTransfer;
        }

        return [
            $productOfferServicePointAvailabilityRequestItemTransfersWithProductOfferReference,
            $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference,
        ];
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfers
     *
     * @return list<string>
     */
    protected function extractProductOfferReferencesFromProductOfferServicePointAvailabilityRequestItemTransfers(
        array $productOfferServicePointAvailabilityRequestItemTransfers
    ): array {
        $productOfferReferences = [];

        foreach ($productOfferServicePointAvailabilityRequestItemTransfers as $productOfferServicePointAvailabilityRequestItemTransfer) {
            $productOfferReferences[] = $productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReferenceOrFail();
        }

        return $productOfferReferences;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers
     * @param list<string> $productOfferReferences
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>
     */
    protected function filterOutProductOfferServicePointAvailabilityResponseItemTransfersWithIrrelevantProductOfferReference(
        array $productOfferServicePointAvailabilityResponseItemTransfers,
        array $productOfferReferences
    ): array {
        $filteredProductOfferServicePointAvailabilityResponseItemTransfers = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            if (
                in_array(
                    $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                    $productOfferReferences,
                    true,
                )
            ) {
                $filteredProductOfferServicePointAvailabilityResponseItemTransfers[] = $productOfferServicePointAvailabilityResponseItemTransfer;
            }
        }

        return $filteredProductOfferServicePointAvailabilityResponseItemTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers
     *
     * @return array<string, array<string, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function getProductOfferServicePointAvailabilityResponseItemTransfersMap(
        array $productOfferServicePointAvailabilityResponseItemTransfers
    ): array {
        $productOfferServicePointAvailabilityResponseItemTransfersMap = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            $servicePointUuid = $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuidOrFail();
            $productOfferReference = $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail();

            $productOfferServicePointAvailabilityResponseItemTransfersMap[$servicePointUuid][$productOfferReference] = (new ProductOfferServicePointAvailabilityResponseItemTransfer())
                ->fromArray($productOfferServicePointAvailabilityResponseItemTransfer->toArray());
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersMap;
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference
     * @param list<string> $servicePointUuids
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function addAvailabilitiesForItemsWithoutProductOfferReference(
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
        array $productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference,
        array $servicePointUuids
    ): array {
        foreach ($productOfferServicePointAvailabilityRequestItemTransfersWithoutProductOfferReference as $productOfferServicePointAvailabilityRequestItemTransfer) {
            foreach ($servicePointUuids as $servicePointUuid) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] = $this->createUnavailableProductOfferServicePointAvailabilityResponseItem(
                    $productOfferServicePointAvailabilityRequestItemTransfer,
                    $servicePointUuid,
                );
            }
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer
     */
    protected function createUnavailableProductOfferServicePointAvailabilityResponseItem(
        ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer,
        string $servicePointUuid
    ): ProductOfferServicePointAvailabilityResponseItemTransfer {
        return (new ProductOfferServicePointAvailabilityResponseItemTransfer())
            ->setServicePointUuid($servicePointUuid)
            ->setProductOfferReference($productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReference())
            ->setProductConcreteSku($productOfferServicePointAvailabilityRequestItemTransfer->getProductConcreteSkuOrFail())
            ->setIdentifier($productOfferServicePointAvailabilityRequestItemTransfer->getIdentifierOrFail())
            ->setAvailableQuantity(0)
            ->setIsNeverOutOfStock(false)
            ->setIsAvailable(false);
    }
}
