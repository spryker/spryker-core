<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\ItemMetadataTransfer;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SpyOmsOrderItemStateEntityTransfer;
use Generated\Shared\Transfer\SpySalesOrderItemEntityTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\ScheduleTimeOrderItemExpanderPreSavePlugin;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group ScheduleTimeOrderItemExpanderPreSavePluginTest
 */
class ScheduleTimeOrderItemExpanderPreSavePluginTest extends Unit
{
    /**
     * @return void
     */
    public function testExpandOrderItemSetsStateWhenItemHasScheduledTime(): void
    {
        // Arrange
        $scheduledAt = '2023-12-01 10:00:00';
        $stateId = 1;
        $stateName = 'test-state';

        $itemTransfer = $this->createItemTransferWithScheduledTime($scheduledAt, $stateId, $stateName);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();
        $quoteTransfer = $this->createQuoteTransfer();

        // Act
        $scheduleTimeOrderItemExpanderPreSavePlugin = new ScheduleTimeOrderItemExpanderPreSavePlugin();
        $resultSalesOrderItemEntityTransfer = $scheduleTimeOrderItemExpanderPreSavePlugin->expandOrderItem(
            $quoteTransfer,
            $itemTransfer,
            $salesOrderItemEntityTransfer,
        );

        // Assert
        $this->assertSame($stateId, $resultSalesOrderItemEntityTransfer->getFkOmsOrderItemState());
        $this->assertInstanceOf(
            SpyOmsOrderItemStateEntityTransfer::class,
            $resultSalesOrderItemEntityTransfer->getState(),
        );
        $this->assertSame($stateName, $resultSalesOrderItemEntityTransfer->getState()->getName());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemDoesNotChangeStateWhenItemHasNoScheduledTime(): void
    {
        // Arrange
        $itemTransfer = $this->createItemTransferWithoutScheduledTime();
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();
        $quoteTransfer = $this->createQuoteTransfer();

        // Act
        $scheduleTimeOrderItemExpanderPreSavePlugin = new ScheduleTimeOrderItemExpanderPreSavePlugin();
        $resultSalesOrderItemEntityTransfer = $scheduleTimeOrderItemExpanderPreSavePlugin->expandOrderItem(
            $quoteTransfer,
            $itemTransfer,
            $salesOrderItemEntityTransfer,
        );

        // Assert
        $this->assertNull($resultSalesOrderItemEntityTransfer->getFkOmsOrderItemState());
        $this->assertNull($resultSalesOrderItemEntityTransfer->getState());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemHandlesItemWithScheduledTimeButNoState(): void
    {
        // Arrange
        $scheduledAt = '2023-12-01 10:00:00';
        $stateId = 1;

        $itemTransfer = (new ItemBuilder())
            ->withMetadata([
                ItemMetadataTransfer::SCHEDULED_AT => $scheduledAt,
            ])
            ->build();
        $itemTransfer->setFkOmsOrderItemState($stateId);

        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();
        $quoteTransfer = $this->createQuoteTransfer();

        // Act
        $scheduleTimeOrderItemExpanderPreSavePlugin = new ScheduleTimeOrderItemExpanderPreSavePlugin();
        $resultSalesOrderItemEntityTransfer = $scheduleTimeOrderItemExpanderPreSavePlugin->expandOrderItem(
            $quoteTransfer,
            $itemTransfer,
            $salesOrderItemEntityTransfer,
        );

        // Assert
        $this->assertSame($stateId, $resultSalesOrderItemEntityTransfer->getFkOmsOrderItemState());
        $this->assertNull($resultSalesOrderItemEntityTransfer->getState());
    }

    /**
     * @return void
     */
    public function testExpandOrderItemPreservesExistingOmsStateForUpdateOperations(): void
    {
        // Arrange
        $scheduledAt = '2023-12-01 10:00:00';
        $initialStateId = 1;
        $initialStateName = 'initial-test-state';
        $existingStateId = 2;

        $itemTransfer = $this->createItemTransferWithScheduledTime($scheduledAt, $initialStateId, $initialStateName);
        $salesOrderItemEntityTransfer = new SpySalesOrderItemEntityTransfer();
        $salesOrderItemEntityTransfer->setFkOmsOrderItemState($existingStateId);
        $quoteTransfer = $this->createQuoteTransfer();

        // Act
        $scheduleTimeOrderItemExpanderPreSavePlugin = new ScheduleTimeOrderItemExpanderPreSavePlugin();
        $resultSalesOrderItemEntityTransfer = $scheduleTimeOrderItemExpanderPreSavePlugin->expandOrderItem(
            $quoteTransfer,
            $itemTransfer,
            $salesOrderItemEntityTransfer,
        );

        // Assert
        $this->assertSame($initialStateId, $resultSalesOrderItemEntityTransfer->getFkOmsOrderItemState());
        $this->assertInstanceOf(
            SpyOmsOrderItemStateEntityTransfer::class,
            $resultSalesOrderItemEntityTransfer->getState(),
        );
        $this->assertSame($initialStateName, $resultSalesOrderItemEntityTransfer->getState()->getName());
    }

    /**
     * @param string $scheduledAt
     * @param int $stateId
     * @param string $stateName
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferWithScheduledTime(string $scheduledAt, int $stateId, string $stateName): ItemTransfer
    {
        $stateTransfer = (new ItemStateTransfer())
            ->setName($stateName);

        return (new ItemBuilder())
            ->withMetadata([
                ItemMetadataTransfer::SCHEDULED_AT => $scheduledAt,
            ])
            ->build()
            ->setFkOmsOrderItemState($stateId)
            ->setState($stateTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function createItemTransferWithoutScheduledTime(): ItemTransfer
    {
        return (new ItemBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransfer(): QuoteTransfer
    {
        return (new QuoteBuilder())->build();
    }
}
