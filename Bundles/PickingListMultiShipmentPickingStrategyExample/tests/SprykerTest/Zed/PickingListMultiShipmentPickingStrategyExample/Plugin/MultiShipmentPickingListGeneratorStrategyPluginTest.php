<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample\Plugin;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PickingListOrderItemGroupTransfer;
use Generated\Shared\Transfer\StockTransfer;
use SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PickingListMultiShipmentPickingStrategyExample
 * @group Plugin
 * @group MultiShipmentPickingListGeneratorStrategyPluginTest
 * Add your own group annotations below this line
 */
class MultiShipmentPickingListGeneratorStrategyPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample\PickingListMultiShipmentPickingStrategyExampleBusinessTester
     */
    protected PickingListMultiShipmentPickingStrategyExampleBusinessTester $tester;

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnTrueWhenPickingListStrategyIsCorrect(): void
    {
        // Arrange
        $pickingListStrategy = $this->tester->getModuleConfig()
            ->getPickingListStrategy();

        $stockTransfer = $this->tester->createStockTransfer([
            StockTransfer::PICKING_LIST_STRATEGY => $pickingListStrategy,
        ]);

        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer);

        //Act
        $isApplicable = $this->tester
            ->createMultiShipmentPickingListGeneratorStrategyPlugin()
            ->isApplicable($pickingListOrderItemGroupTransfer);

        //Assert
        $this->assertTrue($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnFalseWhenPickingListStrategyIsNotCorrect(): void
    {
        // Arrange
        $stockTransfer = $this->tester->createStockTransfer();
        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer);

        //Act
        $isApplicable = $this->tester
            ->createMultiShipmentPickingListGeneratorStrategyPlugin()
            ->isApplicable($pickingListOrderItemGroupTransfer);

        //Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnFalseWhenPickingListStrategyNotSet(): void
    {
        // Arrange
        $stockTransfer = $this->tester->createStockTransfer([
            StockTransfer::PICKING_LIST_STRATEGY => null,
        ]);

        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer())
            ->setWarehouse($stockTransfer);

        //Act
        $isApplicable = $this->tester
            ->createMultiShipmentPickingListGeneratorStrategyPlugin()
            ->isApplicable($pickingListOrderItemGroupTransfer);

        //Assert
        $this->assertFalse($isApplicable);
    }

    /**
     * @return void
     */
    public function testIsApplicableShouldReturnFalseWhenWarehouseNotSet(): void
    {
        // Arrange
        $pickingListOrderItemGroupTransfer = (new PickingListOrderItemGroupTransfer());

        //Act
        $isApplicable = $this->tester
            ->createMultiShipmentPickingListGeneratorStrategyPlugin()
            ->isApplicable($pickingListOrderItemGroupTransfer);

        //Assert
        $this->assertFalse($isApplicable);
    }
}
