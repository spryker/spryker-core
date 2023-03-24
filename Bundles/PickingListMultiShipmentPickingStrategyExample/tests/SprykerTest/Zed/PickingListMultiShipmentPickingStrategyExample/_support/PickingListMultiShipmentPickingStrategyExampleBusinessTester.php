<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace SprykerTest\Zed\PickingListMultiShipmentPickingStrategyExample;

use Codeception\Actor;
use DateTime;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\StockBuilder;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
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
class PickingListMultiShipmentPickingStrategyExampleBusinessTester extends Actor
{
    use _generated\PickingListMultiShipmentPickingStrategyExampleBusinessTesterActions;

    /**
     * @var string
     */
    public const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function getOrderTransfer(int $idSalesOrder): OrderTransfer
    {
        return $this->getLocator()
            ->sales()
            ->facade()
            ->findOrderByIdSalesOrder($idSalesOrder);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function expandOrderItemsWithShipment(
        OrderTransfer $orderTransfer
    ): OrderTransfer {
        $shipmentTransfer = $this->createShipmentTransfer();
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $orderTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createPersistedOrderTransfer(): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrder(
            [],
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return $this->getLocator()
            ->sales()
            ->facade()
            ->findOrderByIdSalesOrder(
                $saveOrderTransfer->getIdSalesOrder(),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function createPersistedOrderTransferFromQuote(QuoteTransfer $quoteTransfer): OrderTransfer
    {
        $saveOrderTransfer = $this->haveOrderFromQuote(
            $quoteTransfer,
            static::DEFAULT_OMS_PROCESS_NAME,
        );

        return $this->getOrderTransfer(
            $saveOrderTransfer->getIdSalesOrder(),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function createQuoteTransferWithThreeItems(): QuoteTransfer
    {
        return (new QuoteBuilder())
            ->withStore()
            ->withItem()
            ->withItem()
            ->withItem()
            ->withCustomer()
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->build();
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

    /**
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function createShipmentTransfer(): ShipmentTransfer
    {
        return (new ShipmentTransfer())
            ->setRequestedDeliveryDate(
                (new DateTime())->format('Y-m-d'),
            );
    }

    /**
     * @return \Spryker\Zed\PickingListExtension\Dependency\Plugin\PickingListGeneratorStrategyPluginInterface
     */
    public function createMultiShipmentPickingListGeneratorStrategyPlugin(): PickingListGeneratorStrategyPluginInterface
    {
        return new MultiShipmentPickingListGeneratorStrategyPlugin();
    }
}
