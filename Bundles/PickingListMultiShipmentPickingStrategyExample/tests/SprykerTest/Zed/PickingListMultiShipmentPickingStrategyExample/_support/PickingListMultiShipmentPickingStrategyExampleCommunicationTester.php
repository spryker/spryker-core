<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample;

use Codeception\Actor;
use Generated\Shared\DataBuilder\StockBuilder;
use Generated\Shared\Transfer\StockTransfer;
use Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface;
use Spryker\Zed\PickingListMultiShipmentPickingStrategyExample\Communication\Plugin\PickingList\MultiShipmentPickingListGeneratorStrategyPlugin;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class PickingListMultiShipmentPickingStrategyExampleCommunicationTester extends Actor
{
    use _generated\PickingListMultiShipmentPickingStrategyExampleCommunicationTesterActions;

    /**
     * @return \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface
     */
    public function createMultiShipmentPickingListGeneratorStrategyPlugin(): PickingListGeneratorStrategyPluginInterface
    {
        return new MultiShipmentPickingListGeneratorStrategyPlugin();
    }

    /**
     * @param array<string, mixed> $seedData
     *
     * @return \Generated\Shared\Transfer\StockTransfer
     */
    public function createStockTransfer(array $seedData = []): StockTransfer
    {
        return (new StockBuilder($seedData))->build();
    }
}
