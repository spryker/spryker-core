<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Asset\Saver;

use ArrayObject;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;

class SalesOrderItemSspAssetSaver implements SalesOrderItemSspAssetSaverInterface
{
    use TransactionTrait;

    /**
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager
     * @param \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface $selfServicePortalRepository
     */
    public function __construct(
        protected SelfServicePortalEntityManagerInterface $selfServicePortalEntityManager,
        protected SelfServicePortalRepositoryInterface $selfServicePortalRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
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
     * @return array<int>
     */
    protected function getSalesOrderItemIds(SaveOrderTransfer $saveOrderTransfer): array
    {
        $salesOrderItemIds = [];
        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $salesOrderItemIds[$itemTransfer->getGroupKey()] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return $salesOrderItemIds;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\SspAssetTransfer|null>
     */
    protected function extractSspAssetDataFromQuoteItems(QuoteTransfer $quoteTransfer): array
    {
        $quoteItemsSspAssetData = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $sspAssetTransfer = $itemTransfer->getSspAsset();
            if (!$sspAssetTransfer) {
                continue;
            }

            $quoteItemsSspAssetData[$itemTransfer->getGroupKey()] = $sspAssetTransfer;
        }

        return $quoteItemsSspAssetData;
    }

    /**
     * @param array<int> $salesOrderItemIds
     * @param array<string, \Generated\Shared\Transfer\SspAssetTransfer|null> $quoteItemsSspAssetData
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
     * @param array<int> $salesOrderItemIds
     * @param array<string, \Generated\Shared\Transfer\SspAssetTransfer|null> $quoteItemsSspAssetData
     * @param array<string, \Generated\Shared\Transfer\SspAssetTransfer> $assetsIndexedByReference
     *
     * @return bool
     */
    protected function executePersistSalesOrderItemSspAssetsTransaction(
        array $salesOrderItemIds,
        array $quoteItemsSspAssetData,
        array $assetsIndexedByReference
    ): bool {
        foreach ($quoteItemsSspAssetData as $itemGroupKey => $sspAssetTransfer) {
            if (!isset($salesOrderItemIds[$itemGroupKey]) || !$sspAssetTransfer) {
                continue;
            }

            $idSalesOrderItem = $salesOrderItemIds[$itemGroupKey];
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
     * @param array<string, \Generated\Shared\Transfer\SspAssetTransfer|null> $quoteItemsSspAssetData
     *
     * @return array<string>
     */
    protected function extractAssetReferences(array $quoteItemsSspAssetData): array
    {
        $references = [];

        foreach ($quoteItemsSspAssetData as $sspAssetTransfer) {
            if (!$sspAssetTransfer || !$sspAssetTransfer->getReference()) {
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

        return $this->selfServicePortalRepository->getSspAssetCollection($sspAssetCriteriaTransfer);
    }

    /**
     * @param \ArrayObject<\Generated\Shared\Transfer\SspAssetTransfer> $sspAssets
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

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemSspAssetTransfer
     */
    protected function createSalesOrderItemSspAssetTransfer(SspAssetTransfer $sspAssetTransfer, int $idSalesOrderItem): SalesOrderItemSspAssetTransfer
    {
        $salesOrderItemSspAssetTransfer = (new SalesOrderItemSspAssetTransfer())
            ->fromArray($sspAssetTransfer->toArray(), true);

        $salesOrderItemSspAssetTransfer->setFkSalesOrderItem($idSalesOrderItem);

        return $salesOrderItemSspAssetTransfer;
    }
}
