<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ShipmentCartConnector\ShipmentCartConnectorConfig;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCartConnector
 * @group Business
 * @group Facade
 * @group ShipmentCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class ShipmentCartConnectorFacadeTest extends Unit
{
    public const SKU = 'sku';
    public const CURRENCY_ISO_CODE = 'USD';
    public const DEFAULT_PRICE_LIST = [
        'DE' => [
            'EUR' => [],
        ],
    ];

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_ADD
     */
    protected const OPERATION_ADD = 'add';

    /**
     * @uses \Spryker\Zed\Cart\CartConfig::OPERATION_REMOVE
     */
    protected const OPERATION_REMOVE = 'remove';

    /**
     * @var \SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testUpdateShipmentPriceShouldUpdatePriceBasedOnCurrency(): void
    {
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(static::CURRENCY_ISO_CODE);
        $shipmentMethodTransfer->setStoreCurrencyPrice(-1);

        $cartChangeTransfer = $this->createCartCartChangeTransfer($shipmentMethodTransfer, $storeTransfer);

        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        $quoteTransfer = $updatedCartChangeTransfer->getQuote();

        $this->assertSame($quoteTransfer->getShipment()->getMethod()->getCurrencyIsoCode(), $quoteTransfer->getCurrency()->getCode());

        $price = $quoteTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
        $this->assertNotEmpty($price);
        $this->assertNotEquals(-1, $price);
    }

    /**
     * @return void
     */
    public function testUpdateShipmentPriceShouldUpdatePriceBasedOnCurrencyWithItemLevelShipments(): void
    {
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(static::CURRENCY_ISO_CODE);
        $shipmentMethodTransfer->setStoreCurrencyPrice(-1);

        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        $quoteTransfer = $updatedCartChangeTransfer->getQuote();
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $this->assertSame(
                $itemTransfer->getShipment()->getMethod()->getCurrencyIsoCode(),
                $quoteTransfer->getCurrency()->getCode()
            );

            $price = $itemTransfer->getShipment()->getMethod()->getStoreCurrencyPrice();
            $this->assertNotEmpty($price);
            $this->assertNotEquals(-1, $price);
        }
    }

    /**
     * @return void
     */
    public function testUpdateShipmentPriceWithShipmentMethodSourcePrices(): void
    {
        // Arrange
        $sourcePrice = 322;
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(static::CURRENCY_ISO_CODE);
        $shipmentMethodTransfer->setSourcePrice((new MoneyValueBuilder([MoneyValueTransfer::GROSS_AMOUNT => $sourcePrice]))->build());

        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $sourcePrice,
            $updatedCartChangeTransfer->getQuote()->getExpenses()->getIterator()->current()->getUnitGrossPrice()
        );
    }

    /**
     * @return void
     */
    public function testUpdateShipmentPriceWithQuoteShipmentMethodSourcePrice(): void
    {
        // Arrange
        $sourcePrice = 322;
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $shipmentMethodTransfer->setCurrencyIsoCode(static::CURRENCY_ISO_CODE)
            ->setSourcePrice((new MoneyValueBuilder([MoneyValueTransfer::GROSS_AMOUNT => $sourcePrice]))->build());

        $cartChangeTransfer = $this->createCartChangeTransferWithQuoteLevelShipment($shipmentMethodTransfer, $storeTransfer);

        // Act
        $updatedCartChangeTransfer = $shipmentCartConnectorFacade->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $sourcePrice,
            $updatedCartChangeTransfer->getQuote()->getExpenses()->getIterator()->current()->getUnitGrossPrice()
        );
    }

    /**
     * @return void
     */
    public function testValidateShipmentShouldReturnFalseWhenSelectedShipmentHaveNoPrice(): void
    {
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->createCartCartChangeTransfer($shipmentMethodTransfer, $storeTransfer);

        $cartChangeTransfer->getQuote()->getCurrency()->setCode('LTL');

        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateShipmentShouldReturnFalseWhenSelectedShipmentHaveNoPriceWithItemLevelShipments(): void
    {
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        $cartChangeTransfer->getQuote()->getCurrency()->setCode('LTL');

        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        $this->assertFalse($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(1, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateShipmentShouldReturnTrueWhenSelectedShipmentHavePrice(): void
    {
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->createCartCartChangeTransfer($shipmentMethodTransfer, $storeTransfer);

        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testValidateShipmentShouldReturnTrueWhenSelectedShipmentHavePriceWithItemLevelShipments(): void
    {
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);

        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);

        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        $cartPreCheckResponseTransfer = $shipmentCartConnectorFacade->validateShipment($cartChangeTransfer);

        $this->assertTrue($cartPreCheckResponseTransfer->getIsSuccess());
        $this->assertCount(0, $cartPreCheckResponseTransfer->getMessages());
    }

    /**
     * @return void
     */
    public function testClearShipmentMethodShouldClearItemLevelShipmentOnAddOperation(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_ADD);

        // Act
        $cartChangeTransfer = $shipmentCartConnectorFacade->clearShipmentMethod($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getQuote()->getItems()->getIterator()->current();

        // Assert
        $this->assertEmpty($itemTransfer->getShipment()->getMethod());
    }

    /**
     * @return void
     */
    public function testClearShipmentMethodShouldClearItemLevelShipmentOnRemoveOperation(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_REMOVE);

        // Act
        $cartChangeTransfer = $shipmentCartConnectorFacade->clearShipmentMethod($cartChangeTransfer);
        $itemTransfer = $cartChangeTransfer->getQuote()->getItems()->getIterator()->current();

        // Assert
        $this->assertEmpty($itemTransfer->getShipment()->getMethod());
    }

    /**
     * @return void
     */
    public function testClearShipmentMethodShouldClearShipmentExpenses(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_ADD);

        // Act
        $cartChangeTransfer = $shipmentCartConnectorFacade->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $this->assertEmpty($cartChangeTransfer->getQuote()->getExpenses());
    }

    /**
     * @return void
     */
    public function testClearShipmentMethodShouldNotClearItemLevelShipment(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->createCartChangeTransferWithItemLevelShipments($shipmentMethodTransfer, $storeTransfer);

        // Act
        $cartChangeTransfer = $shipmentCartConnectorFacade->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $itemTransfer = $cartChangeTransfer->getQuote()->getItems()->getIterator()->current();
        $this->assertNotEmpty($itemTransfer->getShipment()->getMethod());
    }

    /**
     * @return void
     */
    public function testClearShipmentMethodShouldClearQuoteLevelShipment(): void
    {
        // Arrange
        $shipmentCartConnectorFacade = $this->tester->getFacade();
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $shipmentMethodTransfer = $this->tester->haveShipmentMethod([], [], static::DEFAULT_PRICE_LIST, [$storeTransfer->getIdStore()]);
        $cartChangeTransfer = $this->createCartChangeTransferWithQuoteLevelShipment($shipmentMethodTransfer, $storeTransfer);
        $cartChangeTransfer->setOperation(static::OPERATION_ADD);

        // Act
        $cartChangeTransfer = $shipmentCartConnectorFacade->clearShipmentMethod($cartChangeTransfer);

        // Assert
        $this->assertEmpty($cartChangeTransfer->getQuote()->getShipment()->getMethod());
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartCartChangeTransfer(ShipmentMethodTransfer $shipmentMethodTransfer, StoreTransfer $storeTransfer): CartChangeTransfer
    {
        $cartChangeTransfer = (new CartChangeBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->withExpense()
            ->build();

        $shipmentTransfer = (new ShipmentBuilder())->build();

        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $quoteTransfer->setShipment($shipmentTransfer);
        $quoteTransfer = $this->removeItemLevelShipments($quoteTransfer);

        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithItemLevelShipments(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): CartChangeTransfer {
        $cartChangeTransfer = (new CartChangeBuilder())->build();

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->build();

        $shipmentTransfer = (new ShipmentBuilder())->build();
        $shipmentTransfer->setMethod($shipmentMethodTransfer);

        $shipmentExpense = (new ExpenseBuilder())->build();
        $shipmentExpense->setType(ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE);
        $shipmentExpense->setShipment($shipmentTransfer);

        $itemTransfer = (new ItemBuilder())->build();
        $itemTransfer->setSku(static::SKU);
        $itemTransfer->setGroupKey(static::SKU);
        $itemTransfer->setShipment($shipmentTransfer);

        $quoteTransfer->addItem($itemTransfer);
        $quoteTransfer->addExpense($shipmentExpense);

        $cartChangeTransfer->setQuote($quoteTransfer);

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function createCartChangeTransferWithQuoteLevelShipment(
        ShipmentMethodTransfer $shipmentMethodTransfer,
        StoreTransfer $storeTransfer
    ): CartChangeTransfer {
        $shipmentTransfer = (new ShipmentBuilder())->build()
            ->setMethod($shipmentMethodTransfer);

        $shipmentExpense = (new ExpenseBuilder())->build()
            ->setType(ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE)
            ->setShipment($shipmentTransfer);

        $itemTransfer = (new ItemBuilder())->build()
            ->setSku(static::SKU)
            ->setGroupKey(static::SKU);

        $quoteTransfer = (new QuoteBuilder())
            ->withStore($storeTransfer->toArray())
            ->withCurrency()
            ->build()
            ->addItem($itemTransfer)
            ->addExpense($shipmentExpense)
            ->setShipment($shipmentTransfer);

        return (new CartChangeBuilder())->build()
            ->setQuote($quoteTransfer);
    }
}
