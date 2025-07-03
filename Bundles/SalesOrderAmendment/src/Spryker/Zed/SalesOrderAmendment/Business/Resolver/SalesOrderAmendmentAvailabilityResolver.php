<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Business\Resolver;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\SellableItemResponseTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Generated\Shared\Transfer\SellableItemsResponseTransfer;
use Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface;

class SalesOrderAmendmentAvailabilityResolver implements SalesOrderAmendmentAvailabilityResolverInterface
{
    /**
     * @param \Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceInterface $salesOrderAmendmentService
     */
    public function __construct(protected SalesOrderAmendmentServiceInterface $salesOrderAmendmentService)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     * @param \Generated\Shared\Transfer\SellableItemsResponseTransfer $sellableItemsResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SellableItemsResponseTransfer
     */
    public function resolve(
        SellableItemsRequestTransfer $sellableItemsRequestTransfer,
        SellableItemsResponseTransfer $sellableItemsResponseTransfer
    ): SellableItemsResponseTransfer {
        if (!$sellableItemsRequestTransfer->getQuote()) {
            return $sellableItemsResponseTransfer;
        }

        $sellableItemRequestTransfersGroupedByGroupKey = $this->getSellableItemRequestTransfersGroupedByGroupKey($sellableItemsRequestTransfer);
        $originalSalesOrderItemQuantitiesIndexedByGroupKey = $this->getOriginalSalesOrderItemQuantitiesIndexedByGroupKey($sellableItemsRequestTransfer);

        foreach ($sellableItemsResponseTransfer->getSellableItemResponses() as $sellableItemResponseTransfer) {
            $this->resolveSellableItemResponse(
                $sellableItemResponseTransfer,
                $sellableItemRequestTransfersGroupedByGroupKey,
                $originalSalesOrderItemQuantitiesIndexedByGroupKey,
            );
        }

        return $sellableItemsResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemResponseTransfer $sellableItemResponseTransfer
     * @param array<string, array<\Generated\Shared\Transfer\SellableItemRequestTransfer>> $sellableItemRequestTransfersGroupedByGroupKey
     * @param array<string, int> $originalSalesOrderItemQuantitiesIndexedByGroupKey
     *
     * @return void
     */
    protected function resolveSellableItemResponse(
        SellableItemResponseTransfer $sellableItemResponseTransfer,
        array $sellableItemRequestTransfersGroupedByGroupKey,
        array $originalSalesOrderItemQuantitiesIndexedByGroupKey
    ): void {
        if ($sellableItemResponseTransfer->getIsSellable()) {
            return;
        }

        $key = $this->salesOrderAmendmentService->buildOriginalSalesOrderItemGroupKey(
            $this->createItemTransfer($sellableItemResponseTransfer->getSkuOrFail(), $sellableItemResponseTransfer->getProductAvailabilityCriteria()),
        );

        if (!isset($sellableItemRequestTransfersGroupedByGroupKey[$key], $originalSalesOrderItemQuantitiesIndexedByGroupKey[$key])) {
            return;
        }

        $originalSalerOrderItemQuantity = $originalSalesOrderItemQuantitiesIndexedByGroupKey[$key];
        $availableForAmendment = $sellableItemResponseTransfer->getAvailableQuantityOrFail()->isNegative()
            ? $originalSalerOrderItemQuantity
            : $originalSalerOrderItemQuantity + $sellableItemResponseTransfer->getAvailableQuantityOrFail()->toFloat();

        $sellableItemResponseTransfer->setAvailableQuantity($availableForAmendment);

        foreach ($sellableItemRequestTransfersGroupedByGroupKey[$key] as $sellableItemRequestTransfer) {
            $requestQuantity = $sellableItemRequestTransfer->getQuantityOrFail()->toFloat();
            if ($availableForAmendment < $requestQuantity) {
                return;
            }
        }

        $sellableItemResponseTransfer->setIsSellable(true);
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return array<string, array<\Generated\Shared\Transfer\SellableItemRequestTransfer>>
     */
    protected function getSellableItemRequestTransfersGroupedByGroupKey(SellableItemsRequestTransfer $sellableItemsRequestTransfer): array
    {
        $sellableItemRequestTransfersGroupedByGroupKey = [];
        foreach ($sellableItemsRequestTransfer->getSellableItemRequests() as $sellableItemRequestTransfer) {
            $key = $this->salesOrderAmendmentService->buildOriginalSalesOrderItemGroupKey(
                $this->createItemTransfer($sellableItemRequestTransfer->getSkuOrFail(), $sellableItemRequestTransfer->getProductAvailabilityCriteria()),
            );

            $sellableItemRequestTransfersGroupedByGroupKey[$key][] = $sellableItemRequestTransfer;
        }

        return $sellableItemRequestTransfersGroupedByGroupKey;
    }

    /**
     * @param \Generated\Shared\Transfer\SellableItemsRequestTransfer $sellableItemsRequestTransfer
     *
     * @return array<string, int>
     */
    protected function getOriginalSalesOrderItemQuantitiesIndexedByGroupKey(SellableItemsRequestTransfer $sellableItemsRequestTransfer): array
    {
        $quantities = [];
        foreach ($sellableItemsRequestTransfer->getQuoteOrFail()->getOriginalSalesOrderItems() as $originalSalesOrderItemTransfer) {
            $key = $originalSalesOrderItemTransfer->getGroupKeyOrFail();
            $quantities[$key] = ($quantities[$key] ?? 0) + $originalSalesOrderItemTransfer->getQuantityOrFail();
        }

        return $quantities;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer|null $productAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransfer(
        string $sku,
        ?ProductAvailabilityCriteriaTransfer $productAvailabilityCriteriaTransfer
    ): ItemTransfer {
        $itemTransfer = (new ItemTransfer())->setSku($sku);
        if ($productAvailabilityCriteriaTransfer) {
            $itemTransfer->fromArray($productAvailabilityCriteriaTransfer->toArray(), true);
        }

        return $itemTransfer;
    }
}
