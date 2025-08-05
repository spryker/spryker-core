<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\Sales;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ItemStateTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Shared\Oms\OmsConfig;
use Spryker\Zed\Oms\Business\OmsFacadeInterface;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalBusinessFactory;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\SspServiceCancellableOrderItemExpanderPlugin;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group Sales
 * @group SspServiceCancellableOrderItemExpanderPluginTest
 */
class SspServiceCancellableOrderItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PROCESS_NAME = 'TestProcess';

    /**
     * @var string
     */
    protected const TEST_STATE_NAME = 'TestState';

    /**
     * @var \SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Sales\SspServiceCancellableOrderItemExpanderPlugin
     */
    protected SspServiceCancellableOrderItemExpanderPlugin $plugin;

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    /**
     * @var \Spryker\Zed\Oms\Business\OmsFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $omsFacadeMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->omsFacadeMock = $this->createMock(OmsFacadeInterface::class);

        $factoryMock = $this->createPartialMock(
            SelfServicePortalBusinessFactory::class,
            ['getOmsFacade'],
        );
        $factoryMock->method('getOmsFacade')->willReturn($this->omsFacadeMock);

        $this->plugin = new SspServiceCancellableOrderItemExpanderPlugin();
        $this->plugin->setBusinessFactory($factoryMock);
    }

    public function testExpandSetsIsCancellableToTrueWhenStateFlagIsCancellable(): void
    {
        // Arrange
        $itemTransfer = $this->createItemTransferWithState(static::TEST_PROCESS_NAME, static::TEST_STATE_NAME);

        $this->omsFacadeMock
            ->expects($this->once())
            ->method('getStateFlags')
            ->with(static::TEST_PROCESS_NAME, static::TEST_STATE_NAME)
            ->willReturn([OmsConfig::STATE_TYPE_FLAG_CANCELLABLE]);

        // Act
        $expandedItemTransfers = $this->plugin->expand([$itemTransfer]);

        // Assert
        $this->assertCount(1, $expandedItemTransfers);
        $this->assertTrue($expandedItemTransfers[0]->getIsCancellable());
    }

    public function testExpandSetsIsCancellableToFalseWhenStateFlagIsNotCancellable(): void
    {
        // Arrange
        $itemTransfer = $this->createItemTransferWithState(static::TEST_PROCESS_NAME, static::TEST_STATE_NAME);

        $this->omsFacadeMock
            ->expects($this->once())
            ->method('getStateFlags')
            ->with(static::TEST_PROCESS_NAME, static::TEST_STATE_NAME)
            ->willReturn(['some-other-flag']);

        // Act
        $expandedItemTransfers = $this->plugin->expand([$itemTransfer]);

        // Assert
        $this->assertCount(1, $expandedItemTransfers);
        $this->assertFalse($expandedItemTransfers[0]->getIsCancellable());
    }

    public function testExpandSetsIsCancellableToFalseWhenStateHasNoFlags(): void
    {
        // Arrange
        $itemTransfer = $this->createItemTransferWithState(static::TEST_PROCESS_NAME, static::TEST_STATE_NAME);

        $this->omsFacadeMock
            ->expects($this->once())
            ->method('getStateFlags')
            ->with(static::TEST_PROCESS_NAME, static::TEST_STATE_NAME)
            ->willReturn([]);

        // Act
        $expandedItemTransfers = $this->plugin->expand([$itemTransfer]);

        // Assert
        $this->assertCount(1, $expandedItemTransfers);
        $this->assertFalse($expandedItemTransfers[0]->getIsCancellable());
    }

    public function testExpandSetsIsCancellableToFalseWhenItemHasNoState(): void
    {
        // Arrange
        $itemTransfer = new ItemTransfer();
        $itemTransfer->setProcess(static::TEST_PROCESS_NAME);

        $this->omsFacadeMock
            ->expects($this->never())
            ->method('getStateFlags');

        // Act
        $expandedItemTransfers = $this->plugin->expand([$itemTransfer]);

        // Assert
        $this->assertCount(1, $expandedItemTransfers);
        $this->assertFalse($expandedItemTransfers[0]->getIsCancellable());
    }

    public function testExpandHandlesMultipleItems(): void
    {
        // Arrange
        $itemTransfer1 = $this->createItemTransferWithState(static::TEST_PROCESS_NAME, 'state1');
        $itemTransfer2 = $this->createItemTransferWithState(static::TEST_PROCESS_NAME, 'state2');

        $this->omsFacadeMock
            ->expects($this->exactly(2))
            ->method('getStateFlags')
            ->willReturnMap([
                [static::TEST_PROCESS_NAME, 'state1', [OmsConfig::STATE_TYPE_FLAG_CANCELLABLE]],
                [static::TEST_PROCESS_NAME, 'state2', []],
            ]);

        // Act
        $expandedItemTransfers = $this->plugin->expand([$itemTransfer1, $itemTransfer2]);

        // Assert
        $this->assertCount(2, $expandedItemTransfers);
        $this->assertTrue($expandedItemTransfers[0]->getIsCancellable());
        $this->assertFalse($expandedItemTransfers[1]->getIsCancellable());
    }

    protected function createItemTransferWithState(string $processName, string $stateName): ItemTransfer
    {
        $stateTransfer = new ItemStateTransfer();
        $stateTransfer->setName($stateName);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setProcess($processName);
        $itemTransfer->setState($stateTransfer);

        return $itemTransfer;
    }
}
