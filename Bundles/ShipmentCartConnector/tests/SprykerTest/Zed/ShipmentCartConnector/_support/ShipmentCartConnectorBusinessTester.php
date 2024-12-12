<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector;

use Codeception\Actor;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;
use Generated\Shared\Transfer\StoreTransfer;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 * @method \Spryker\Zed\ShipmentCartConnector\Business\ShipmentCartConnectorFacadeInterface getFacade()
 *
 * @SuppressWarnings(\SprykerTest\Zed\ShipmentCartConnector\PHPMD)
 */
class ShipmentCartConnectorBusinessTester extends Actor
{
    use _generated\ShipmentCartConnectorBusinessTesterActions;

    /**
     * @var string
     */
    public const STORE_NAME_DE = 'DE';

    /**
     * @var array<string, array<string, array<string, mixed>>>
     */
    public const DEFAULT_PRICE_LIST = [
        self::STORE_NAME_DE => [
            'EUR' => [],
        ],
    ];

    /**
     * @var string
     */
    public const CURRENCY_ISO_CODE_USD = 'USD';

    /**
     * @var string
     */
    protected const TEST_SKU = 'sku';

    /**
     * @uses \Spryker\Shared\ShipmentCartConnector\ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE
     *
     * @var string
     */
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartCartChangeTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, StoreTransfer $storeTransfer): CartChangeTransfer
    {
        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->withExpense()
            ->withShipment($this->createShipmentTransfer($shipmentMethodTransfer)->toArray())
            ->build();

        $quoteTransfer = $this->removeItemLevelShipments($quoteTransfer);

        return (new CartChangeBuilder())->build()->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithItemLevelShipments(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): CartChangeTransfer {
        $shipmentTransfer = $this->createShipmentTransfer($shipmentMethodTransfer);

        $itemTransfer = (new ItemBuilder())
            ->build()
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_SKU)
            ->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->withItem($itemTransfer->toArray())
            ->withExpense($this->createExpenseTransfer($shipmentTransfer)->toArray())
            ->build();

        return (new CartChangeBuilder())->withQuote($quoteTransfer->toArray())->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithQuoteLevelShipment(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): CartChangeTransfer {
        $shipmentTransfer = $this->createShipmentTransfer($shipmentMethodTransfer);

        $itemTransfer = (new ItemBuilder())
            ->build()
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_SKU);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->withItem($itemTransfer->toArray())
            ->withExpense($this->createExpenseTransfer($shipmentTransfer)->toArray())
            ->withShipment($shipmentTransfer->toArray())
            ->build();

        return (new CartChangeBuilder())->withQuote($quoteTransfer->toArray())->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function createCartChangeTransferWithDifferentExpenseTypes(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): CartChangeTransfer {
        $shipmentTransfer = $this->createShipmentTransfer($shipmentMethodTransfer);

        $itemTransfer = (new ItemBuilder())
            ->build()
            ->setSku(static::TEST_SKU)
            ->setGroupKey(static::TEST_SKU)
            ->setShipment($shipmentTransfer);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->withItem($itemTransfer->toArray())
            ->withAnotherExpense($this->createExpenseTransfer($shipmentTransfer)->toArray())
            ->withAnotherExpense()
            ->build();

        return (new CartChangeBuilder())->withQuote($quoteTransfer->toArray())->build();
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    protected function createShipmentTransfer(ShipmentMethodTransfer $shipmentMethodTransfer): ShipmentTransfer
    {
        return (new ShipmentBuilder())
            ->build()
            ->setMethod($shipmentMethodTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ExpenseTransfer
     */
    protected function createExpenseTransfer(ShipmentTransfer $shipmentTransfer): ExpenseTransfer
    {
        return (new ExpenseBuilder())
            ->build()
            ->setType(static::SHIPMENT_EXPENSE_TYPE)
            ->setShipment($shipmentTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function removeItemLevelShipments(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment(null);
        }

        return $quoteTransfer;
    }
}
