<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Saver;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;

class SalesOrderItemSspAssetSaver implements SalesOrderItemSspAssetSaverInterface
{
    use TransactionTrait;

    public function __construct(
        protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager,
        protected SelfServicePortalFacadeInterface $selfServicePortalFacade
    ) {
    }

    public function saveSalesOrderItemSspAssetsFromQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $salesOrderItemIds = $this->getSalesOrderItemIds($saveOrderTransfer);
        if (!$salesOrderItemIds) {
            return;
        }

        $quoteItemsSspAssetData = $this->extractSspAssetDataFromQuoteItems($quoteTransfer);
        if (!$quoteItemsSspAssetData) {
            return;
        }

        $this->persistSalesOrderItemSspAssets($salesOrderItemIds, $quoteItemsSspAssetData);
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return array<int, int>
     */
    protected function getSalesOrderItemIds(SaveOrderTransfer $saveOrderTransfer): array
    {
        $salesOrderItemIds = [];
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $salesOrderItemIds[$itemTransfer->getIdSalesOrderItemOrFail()] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    protected function extractSspAssetDataFromQuoteItems(QuoteTransfer $quoteTransfer): array
    {
        $quoteItemsSspAssetData = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $sspAssetTransfer = $itemTransfer->getSspAsset();
            if (!$sspAssetTransfer) {
                continue;
            }

            $quoteItemsSspAssetData[(int)$itemTransfer->getIdSalesOrderItem()] = $sspAssetTransfer;
        }

        return $quoteItemsSspAssetData;
    }

    /**
     * @param array<int|string> $salesOrderItemIds
     * @param array<int, \Generated\Shared\Transfer\SspAssetTransfer> $quoteItemsSspAssetData
     *
     * @return void
     */
    protected function persistSalesOrderItemSspAssets(array $salesOrderItemIds, array $quoteItemsSspAssetData): void
    {
        $assetReferences = $this->extractAssetReferences($quoteItemsSspAssetData);

        if (!$assetReferences) {
            return;
        }

        $assetCollectionTransfer = $this->getAssetCollectionByReferences($assetReferences);
        $assetsIndexedByReference = $this->indexAssetsByReferences($assetCollectionTransfer->getSspAssets());

        $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderItemIds, $quoteItemsSspAssetData, $assetsIndexedByReference) {
            return $this->executePersistSalesOrderItemSspAssetsTransaction($salesOrderItemIds, $quoteItemsSspAssetData, $assetsIndexedByReference);
        });
    }

    /**
     * @param array<int|string> $salesOrderItemIds
     * @param array<int, \Generated\Shared\Transfer\SspAssetTransfer> $quoteItemsSspAssetData
     * @param array<string, \Generated\Shared\Transfer\SspAssetTransfer> $assetsIndexedByReference
     *
     * @return bool
     */
    protected function executePersistSalesOrderItemSspAssetsTransaction(
        array $salesOrderItemIds,
        array $quoteItemsSspAssetData,
        array $assetsIndexedByReference
    ): bool {
        foreach ($quoteItemsSspAssetData as $idSalesOrderItem => $sspAssetTransfer) {
            if (!isset($salesOrderItemIds[$idSalesOrderItem])) {
                continue;
            }

            $reference = $sspAssetTransfer->getReference();

            if (!$reference || !isset($assetsIndexedByReference[$reference])) {
                continue;
            }

            $salesOrderItemSspAssetTransfer = $this->createSalesOrderItemSspAssetTransfer($assetsIndexedByReference[$reference], $idSalesOrderItem);

            $this->selfServicePortalEntityManager->createSalesOrderItemSspAsset(
                $salesOrderItemSspAssetTransfer,
            );
        }

        return true;
    }

    /**
     * @param array<int|string, \Generated\Shared\Transfer\SspAssetTransfer> $quoteItemsSspAssetData
     *
     * @return array<string>
     */
    protected function extractAssetReferences(array $quoteItemsSspAssetData): array
    {
        $references = [];

        foreach ($quoteItemsSspAssetData as $sspAssetTransfer) {
            if (!$sspAssetTransfer->getReference()) {
                continue;
            }

            $references[] = $sspAssetTransfer->getReferenceOrFail();
        }

        return array_unique($references);
    }

    /**
     * @param array<string> $assetReferences
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    protected function getAssetCollectionByReferences(array $assetReferences): object
    {
        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();
        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();

        $sspAssetConditionsTransfer->setReferences($assetReferences);
        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer);

        return $this->selfServicePortalFacade->getSspAssetCollection($sspAssetCriteriaTransfer);
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\SspAssetTransfer> $sspAssets
     *
     * @return array<string, \Generated\Shared\Transfer\SspAssetTransfer>
     */
    protected function indexAssetsByReferences(ArrayObject $sspAssets): array
    {
        $indexedAssets = [];

        foreach ($sspAssets as $sspAssetTransfer) {
            $reference = $sspAssetTransfer->getReference();
            if (!$reference) {
                continue;
            }

            $indexedAssets[$reference] = $sspAssetTransfer;
        }

        return $indexedAssets;
    }

    protected function createSalesOrderItemSspAssetTransfer(SspAssetTransfer $sspAssetTransfer, int $idSalesOrderItem): SalesOrderItemSspAssetTransfer
    {
        $salesOrderItemSspAssetTransfer = (new SalesOrderItemSspAssetTransfer())
            ->fromArray($sspAssetTransfer->toArray(), true);

        $salesOrderItemSspAssetTransfer->setSalesOrderItem((new ItemTransfer())->setIdSalesOrderItem($idSalesOrderItem));

        return $salesOrderItemSspAssetTransfer;
    }
}
