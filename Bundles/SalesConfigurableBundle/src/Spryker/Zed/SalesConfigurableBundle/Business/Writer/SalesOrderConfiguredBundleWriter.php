<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesConfigurableBundle\Business\Writer;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleItemTransfer;
use Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface;
use Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface;

class SalesOrderConfiguredBundleWriter implements SalesOrderConfiguredBundleWriterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleEntityManagerInterface $salesConfigurableBundleEntityManager
     * @param \Spryker\Zed\SalesConfigurableBundle\Persistence\SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
     */
    public function __construct(
        protected SalesConfigurableBundleEntityManagerInterface $salesConfigurableBundleEntityManager,
        protected SalesConfigurableBundleRepositoryInterface $salesConfigurableBundleRepository
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderConfiguredBundlesFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $salesOrderConfiguredBundleTransfers = $this->mapSalesOrderConfiguredBundles($quoteTransfer);

        if (count($salesOrderConfiguredBundleTransfers)) {
            $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderConfiguredBundleTransfers): void {
                $this->executeSaveSalesOrderConfiguredBundlesFromQuoteTransaction($salesOrderConfiguredBundleTransfers);
            });
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderConfiguredBundles(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $quoteTransfer = (new QuoteTransfer())->setItems($salesOrderItemCollectionResponseTransfer->getItems());
        $salesOrderItemIds = $this->extractSalesOrderItemIds($quoteTransfer);
        $salesOrderConfiguredBundleTransfers = $this->mapSalesOrderConfiguredBundles($quoteTransfer);

        if (count($salesOrderConfiguredBundleTransfers)) {
            $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderConfiguredBundleTransfers, $salesOrderItemIds): void {
                $this->executeUpdateSalesOrderConfiguredBundlesTransaction($salesOrderConfiguredBundleTransfers, $salesOrderItemIds);
            });
        }

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer> $salesOrderConfiguredBundleTransfers
     *
     * @return void
     */
    protected function executeSaveSalesOrderConfiguredBundlesFromQuoteTransaction(
        array $salesOrderConfiguredBundleTransfers
    ): void {
        foreach ($salesOrderConfiguredBundleTransfers as $salesOrderConfiguredBundleTransfer) {
            $salesOrderConfiguredBundleTransfer = $this->salesConfigurableBundleEntityManager
                ->createSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer);

            $this->saveSalesOrderConfiguredBundleItems($salesOrderConfiguredBundleTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer> $salesOrderConfiguredBundleTransfers
     * @param list<int> $salesOrderItemIds
     *
     * @return list<\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer>
     */
    protected function executeUpdateSalesOrderConfiguredBundlesTransaction(
        array $salesOrderConfiguredBundleTransfers,
        array $salesOrderItemIds
    ): array {
        $updatedSalesOrderConfiguredBundles = [];
        $salesOrderConfiguredBundleIdsToDelete = $this->salesConfigurableBundleRepository
            ->getSalesOrderConfiguredBundleIdsBySalesOrderItemIds($salesOrderItemIds);

        foreach ($salesOrderConfiguredBundleTransfers as $salesOrderConfiguredBundleTransfer) {
            $salesOrderConfiguredBundleTransfer = $this->salesConfigurableBundleEntityManager
                ->createSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer);

            $updatedSalesOrderConfiguredBundles[] = $this->updateSalesOrderConfiguredBundleItems(
                $salesOrderConfiguredBundleTransfer,
            );
        }

        $this->salesConfigurableBundleEntityManager->deleteSalesOrderConfiguredBundlesByIds($salesOrderConfiguredBundleIdsToDelete);

        return $updatedSalesOrderConfiguredBundles;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return void
     */
    protected function saveSalesOrderConfiguredBundleItems(SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer): void
    {
        foreach ($salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
            $salesOrderConfiguredBundleItemTransfer->setIdSalesOrderConfiguredBundle($salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundle());
            $this->salesConfigurableBundleEntityManager->createSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer
     */
    protected function updateSalesOrderConfiguredBundleItems(
        SalesOrderConfiguredBundleTransfer $salesOrderConfiguredBundleTransfer
    ): SalesOrderConfiguredBundleTransfer {
        $persistedSalesOrderConfiguredBundleItemTransfers = [];
        foreach ($salesOrderConfiguredBundleTransfer->getSalesOrderConfiguredBundleItems() as $salesOrderConfiguredBundleItemTransfer) {
            $salesOrderConfiguredBundleItemTransfer->setIdSalesOrderConfiguredBundle(
                $salesOrderConfiguredBundleTransfer->getIdSalesOrderConfiguredBundleOrFail(),
            );

            $persistedSalesOrderConfiguredBundleItemTransfers[] = $this->salesConfigurableBundleEntityManager
                ->saveSalesOrderConfiguredBundleItemByFkSalesOrderItem($salesOrderConfiguredBundleItemTransfer);
        }

        return $salesOrderConfiguredBundleTransfer->setSalesOrderConfiguredBundleItems(
            new ArrayObject($persistedSalesOrderConfiguredBundleItemTransfers),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer>
     */
    protected function mapSalesOrderConfiguredBundles(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderConfiguredBundleTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getConfiguredBundleItem() && $itemTransfer->getConfiguredBundle()) {
                $salesOrderConfiguredBundleTransfers = $this->mapSalesOrderConfiguredBundle(
                    $itemTransfer,
                    $salesOrderConfiguredBundleTransfers,
                );
            }
        }

        return $salesOrderConfiguredBundleTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return list<int>
     */
    protected function extractSalesOrderItemIds(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderItemIds = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $salesOrderItemIds[] = $itemTransfer->getIdSalesOrderItemOrFail();
        }

        return array_unique($salesOrderItemIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer> $salesOrderConfiguredBundleTransfers
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderConfiguredBundleTransfer>
     */
    protected function mapSalesOrderConfiguredBundle(ItemTransfer $itemTransfer, array $salesOrderConfiguredBundleTransfers): array
    {
        $configuredBundleItemTransfer = $itemTransfer->getConfiguredBundleItem();
        $configuredBundleTransfer = $itemTransfer->getConfiguredBundle();

        $configuredBundleItemTransfer
            ->requireSlot()
            ->getSlot()
                ->requireUuid();

        $configuredBundleTransfer
            ->requireGroupKey()
            ->requireQuantity()
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid()
                ->requireName();

        $salesOrderConfiguredBundleTransfer = (new SalesOrderConfiguredBundleTransfer())
            ->fromArray($configuredBundleTransfer->toArray(), true)
            ->setConfigurableBundleTemplateUuid($configuredBundleTransfer->getTemplate()->getUuid())
            ->setName($configuredBundleTransfer->getTemplate()->getName());

        if (!isset($salesOrderConfiguredBundleTransfers[$configuredBundleTransfer->getGroupKey()])) {
            $salesOrderConfiguredBundleTransfers[$configuredBundleTransfer->getGroupKey()] = $salesOrderConfiguredBundleTransfer;
        }

        $salesOrderConfiguredBundleItemTransfer = (new SalesOrderConfiguredBundleItemTransfer())
            ->setConfigurableBundleTemplateSlotUuid($configuredBundleItemTransfer->getSlot()->getUuid())
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        $salesOrderConfiguredBundleTransfers[$configuredBundleTransfer->getGroupKey()]
            ->addSalesOrderConfiguredBundleItem($salesOrderConfiguredBundleItemTransfer);

        return $salesOrderConfiguredBundleTransfers;
    }
}
