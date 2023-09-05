<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample\Calculator;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;

class ProductOfferServicePointAvailabilityCalculator implements ProductOfferServicePointAvailabilityCalculatorInterface
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
        $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->calculateProductOfferServicePointAvailabilitiesByExampleStrategy(
            $productOfferServicePointAvailabilityCollectionTransfer,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );

        if (count($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid) < count($productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids())) {
            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->addMissingServicePointUuidsToProductOfferServicePointAvailabilities(
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
                $productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids(),
            );
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    public function calculateProductOfferServicePointAvailabilitiesByExampleStrategy(
        ProductOfferServicePointAvailabilityCollectionTransfer $productOfferServicePointAvailabilityCollectionTransfer,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): array {
        $productOfferServicePointAvailabilityRequestItemTransfers = $productOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems()->getArrayCopy();

        $productOfferServicePointAvailabilityResponseItemTransfersMap = $this->getProductOfferServicePointAvailabilityResponseItemTransfersMap(
            $productOfferServicePointAvailabilityCollectionTransfer->getProductOfferServicePointAvailabilityResponseItems()->getArrayCopy(),
        );

        $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = [];

        $servicePointUuids = $productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids();

        foreach ($productOfferServicePointAvailabilityRequestItemTransfers as $productOfferServicePointAvailabilityRequestItemTransfer) {
            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid = $this->calculateProductAvailabilityAtServicePoints(
                $productOfferServicePointAvailabilityRequestItemTransfer,
                $servicePointUuids,
                $productOfferServicePointAvailabilityResponseItemTransfersMap,
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
            );
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer
     * @param list<string> $servicePointUuids
     * @param array<string, array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>> $productOfferServicePointAvailabilityResponseItemTransfersMap
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function calculateProductAvailabilityAtServicePoints(
        ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer,
        array $servicePointUuids,
        array $productOfferServicePointAvailabilityResponseItemTransfersMap,
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
    ): array {
        foreach ($servicePointUuids as $servicePointUuid) {
            $productConcreteSku = $productOfferServicePointAvailabilityRequestItemTransfer->getProductConcreteSkuOrFail();
            $productOfferServicePointAvailabilityResponseItemTransfers = $productOfferServicePointAvailabilityResponseItemTransfersMap[$servicePointUuid][$productConcreteSku] ?? [];

            if (!$productOfferServicePointAvailabilityResponseItemTransfers) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] = $this->createNotAvailableProductOfferServicePointAvailabilityResponseItemTransfer(
                    $productOfferServicePointAvailabilityRequestItemTransfer,
                    $servicePointUuid,
                );

                continue;
            }

            $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid][] = $this->resolveApplicableProductOfferServicePointAvailabilityResponseItemTransfer(
                $productOfferServicePointAvailabilityRequestItemTransfer,
                $productOfferServicePointAvailabilityResponseItemTransfers,
                $servicePointUuid,
            );
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers
     * @param string $servicePointUuid
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer
     */
    protected function resolveApplicableProductOfferServicePointAvailabilityResponseItemTransfer(
        ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer,
        array $productOfferServicePointAvailabilityResponseItemTransfers,
        string $servicePointUuid
    ): ProductOfferServicePointAvailabilityResponseItemTransfer {
        $availableQuantity = 0;

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            if (
                !$this->isAvailabilityApplicableByMerchantReference(
                    $productOfferServicePointAvailabilityRequestItemTransfer,
                    $productOfferServicePointAvailabilityResponseItemTransfer,
                )
            ) {
                continue;
            }

            if ($productOfferServicePointAvailabilityResponseItemTransfer->getIsNeverOutOfStockOrFail()) {
                return (clone $productOfferServicePointAvailabilityResponseItemTransfer)
                    ->setIsAvailable(true)
                    ->setProductOfferReference($productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReferenceOrFail());
            }

            $availableQuantity = $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantityOrFail();
            $requestedQuantity = $productOfferServicePointAvailabilityRequestItemTransfer->getQuantityOrFail();

            if ($availableQuantity >= $requestedQuantity) {
                $resolvedProductOfferServicePointAvailabilityResponseItemTransfer = (clone $productOfferServicePointAvailabilityResponseItemTransfer)
                    ->setIsAvailable(true)
                    ->setProductOfferReference($productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReferenceOrFail());

                $productOfferServicePointAvailabilityResponseItemTransfer->setAvailableQuantity(
                    $availableQuantity - $requestedQuantity,
                );

                return $resolvedProductOfferServicePointAvailabilityResponseItemTransfer;
            }
        }

        return $this->createNotAvailableProductOfferServicePointAvailabilityResponseItemTransfer(
            $productOfferServicePointAvailabilityRequestItemTransfer,
            $servicePointUuid,
            $availableQuantity,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
     *
     * @return bool
     */
    protected function isAvailabilityApplicableByMerchantReference(
        ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer,
        ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
    ): bool {
        if (!$productOfferServicePointAvailabilityRequestItemTransfer->getMerchantReference()) {
            return $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference() === null;
        }

        return $productOfferServicePointAvailabilityRequestItemTransfer->getMerchantReferenceOrFail() === $productOfferServicePointAvailabilityResponseItemTransfer->getMerchantReference();
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers
     *
     * @return array<string, array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>>
     */
    protected function getProductOfferServicePointAvailabilityResponseItemTransfersMap(
        array $productOfferServicePointAvailabilityResponseItemTransfers
    ): array {
        $productOfferServicePointAvailabilityResponseItemTransfersMap = [];

        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            $servicePointUuid = $productOfferServicePointAvailabilityResponseItemTransfer->getServicePointUuidOrFail();
            $productConcreteSku = $productOfferServicePointAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail();

            $productOfferServicePointAvailabilityResponseItemTransfersMap[$servicePointUuid][$productConcreteSku][] = $productOfferServicePointAvailabilityResponseItemTransfer;
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersMap;
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid
     * @param list<string> $servicePointUuids
     *
     * @return array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>>
     */
    protected function addMissingServicePointUuidsToProductOfferServicePointAvailabilities(
        array $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid,
        array $servicePointUuids
    ): array {
        foreach ($servicePointUuids as $servicePointUuid) {
            if (!isset($productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid])) {
                $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid[$servicePointUuid] = [];
            }
        }

        return $productOfferServicePointAvailabilityResponseItemTransfersGroupedByServicePointUuid;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer
     * @param $servicePointUuid
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer
     */
    protected function createNotAvailableProductOfferServicePointAvailabilityResponseItemTransfer(
        ProductOfferServicePointAvailabilityRequestItemTransfer $productOfferServicePointAvailabilityRequestItemTransfer,
        string $servicePointUuid,
        int $quantity = 0
    ): ProductOfferServicePointAvailabilityResponseItemTransfer {
        return (new ProductOfferServicePointAvailabilityResponseItemTransfer())
            ->setProductConcreteSku($productOfferServicePointAvailabilityRequestItemTransfer->getProductConcreteSkuOrFail())
            ->setProductOfferReference($productOfferServicePointAvailabilityRequestItemTransfer->getProductOfferReferenceOrFail())
            ->setMerchantReference($productOfferServicePointAvailabilityRequestItemTransfer->getMerchantReference())
            ->setServicePointUuid($servicePointUuid)
            ->setAvailableQuantity($quantity)
            ->setIsNeverOutOfStock(false)
            ->setIsAvailable(false);
    }
}
