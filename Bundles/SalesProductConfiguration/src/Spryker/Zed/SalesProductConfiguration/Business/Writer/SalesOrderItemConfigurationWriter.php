<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfiguration\Business\Writer;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface;

class SalesOrderItemConfigurationWriter implements SalesOrderItemConfigurationWriterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\SalesProductConfiguration\Persistence\SalesProductConfigurationEntityManagerInterface $salesProductConfigurationEntityManager
     */
    public function __construct(protected SalesProductConfigurationEntityManagerInterface $salesProductConfigurationEntityManager)
    {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveSalesOrderItemConfigurationsFromQuote(QuoteTransfer $quoteTransfer): void
    {
        $salesOrderItemConfigurationTransfers = $this->mapSalesOrderItemConfigurations($quoteTransfer);

        if (!count($salesOrderItemConfigurationTransfers)) {
            return;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($salesOrderItemConfigurationTransfers): void {
            $this->executeSaveSalesOrderItemConfigurationsFromQuoteTransaction($salesOrderItemConfigurationTransfers);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemCollectionResponseTransfer
     */
    public function updateSalesOrderItemConfigurations(
        SalesOrderItemCollectionResponseTransfer $salesOrderItemCollectionResponseTransfer
    ): SalesOrderItemCollectionResponseTransfer {
        $salesOrderItemConfigurationTransfers = $this->mapSalesOrderItemConfigurations(
            (new QuoteTransfer())->setItems($salesOrderItemCollectionResponseTransfer->getItems()),
        );

        if (count($salesOrderItemConfigurationTransfers)) {
            $this->getTransactionHandler()
                ->handleTransaction(function () use ($salesOrderItemConfigurationTransfers) {
                    return $this->executeUpdateSalesOrderItemConfigurationsTransaction($salesOrderItemConfigurationTransfers);
                });
        }

        return $salesOrderItemCollectionResponseTransfer;
    }

    /**
     * @param array<\Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer> $salesOrderItemConfigurationTransfers
     *
     * @return void
     */
    protected function executeSaveSalesOrderItemConfigurationsFromQuoteTransaction(
        array $salesOrderItemConfigurationTransfers
    ): void {
        foreach ($salesOrderItemConfigurationTransfers as $salesOrderItemConfigurationTransfer) {
            $this->salesProductConfigurationEntityManager->saveSalesOrderItemConfiguration($salesOrderItemConfigurationTransfer);
        }
    }

    /**
     * @param list<\Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer> $salesOrderItemConfigurationTransfers
     *
     * @return list<\Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer>
     */
    protected function executeUpdateSalesOrderItemConfigurationsTransaction(
        array $salesOrderItemConfigurationTransfers
    ): array {
        $persistedSalesOrderItemConfigurations = [];
        foreach ($salesOrderItemConfigurationTransfers as $salesOrderItemConfigurationTransfer) {
            $persistedSalesOrderItemConfigurations[] = $this->salesProductConfigurationEntityManager
                ->saveSalesOrderItemConfigurationByFkSalesOrderItem($salesOrderItemConfigurationTransfer);
        }

        return $persistedSalesOrderItemConfigurations;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer>
     */
    protected function mapSalesOrderItemConfigurations(QuoteTransfer $quoteTransfer): array
    {
        $salesOrderItemConfigurationTransfers = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductConfigurationInstance()) {
                $salesOrderItemConfigurationTransfers = $this->mapSalesOrderItemConfiguration(
                    $itemTransfer,
                    $salesOrderItemConfigurationTransfers,
                );
            }
        }

        return $salesOrderItemConfigurationTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<\Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer> $salesOrderItemConfigurationTransfers
     *
     * @return array<\Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer>
     */
    protected function mapSalesOrderItemConfiguration(ItemTransfer $itemTransfer, array $salesOrderItemConfigurationTransfers): array
    {
        $this->assertItemRequirements($itemTransfer);

        $salesOrderItemConfigurationTransfer = (new SalesOrderItemConfigurationTransfer())
            ->fromArray($itemTransfer->getProductConfigurationInstanceOrFail()->toArray(), true)
            ->setIdSalesOrderItem($itemTransfer->getIdSalesOrderItem());

        if (!isset($salesOrderItemConfigurationTransfers[$itemTransfer->getIdSalesOrderItem()])) {
            $salesOrderItemConfigurationTransfers[$itemTransfer->getIdSalesOrderItem()] = $salesOrderItemConfigurationTransfer;
        }

        return $salesOrderItemConfigurationTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function assertItemRequirements(ItemTransfer $itemTransfer): void
    {
        $itemTransfer
            ->requireIdSalesOrderItem()
            ->getProductConfigurationInstanceOrFail()
                ->requireConfiguratorKey();
    }
}
