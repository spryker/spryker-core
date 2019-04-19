<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group ShipmentCartConnector
 * @group Business
 * @group Model
 * @group ShipmentCartExpanderTest
 * Add your own group annotations below this line
 */
class ShipmentCartExpanderTest extends Test
{
    public const DEFAULT_STORE_NAME = 'DE';

    public const CURRENCY_CODE_EUR = 'EUR';
    public const CURRENCY_CODE_USD = 'USD';
    /**
     * @var \SprykerTest\Zed\ShipmentCartConnector\ShipmentCartConnectorBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR]);
        $this->tester->haveCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD]);
    }

    /**
     * @dataProvider updateShipmentPriceWithQuoteLevelShipment
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return void
     */
    public function testUpdateShipmentPriceWithQuoteLevelShipment(
        CartChangeTransfer $cartChangeTransfer
    ): void {
        // Act
        $actualCartChangeTransfer = $this->tester->getFacade()->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $cartChangeTransfer,
            $actualCartChangeTransfer,
            'Shipment price should not have been changed.'
        );
    }

    /**
     * @return array
     */
    public function updateShipmentPriceWithQuoteLevelShipment(): array
    {
        return [
            'quote has not shipment method; shipment price should not have been changed' => $this->getDataWithoutShipment(),
            'quote currency and shipment currency are the same; shipment price should not have been changed' => $this->getDataWithQuoteLevelShipmentWhereQuoteCurrencyAndShipmentCurrencyAreSame(),
        ];
    }

    /**
     * @return array
     */
    public function getDataWithoutShipment(): array
    {
        return [
            (new CartChangeBuilder())
                ->withQuote(
                    (new QuoteBuilder())
                        ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                )
                ->build(),
        ];
    }

    /**
     * @return array
     */
    public function getDataWithQuoteLevelShipmentWhereQuoteCurrencyAndShipmentCurrencyAreSame(): array
    {
        return [
            (new CartChangeBuilder())
                ->withQuote(
                    (new QuoteBuilder())
                        ->withShipment(
                            (new ShipmentBuilder())
                                ->withMethod(
                                    (new ShipmentMethodBuilder([ShipmentMethodTransfer::CURRENCY_ISO_CODE => static::CURRENCY_CODE_EUR]))
                                )
                        )
                        ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                )
                ->build(),
        ];
    }

    /**
     * @dataProvider updateShipmentPriceWithItemLevelShipment
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array $expectedPrices
     *
     * @return void
     */
    public function testUpdateShipmentPriceWithItemLevelShipmentBasedOnQuoteCurrency(
        CartChangeTransfer $cartChangeTransfer,
        array $expectedPrices
    ): void {
        // Arrange
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            $shipmentTransfer = $this->tester->haveShipmentMethod([
                ShipmentMethodTransfer::NAME => $itemTransfer->getShipment()->getMethod()->getName(),
            ]);
            $itemTransfer->getShipment()->getMethod()->setIdShipmentMethod($shipmentTransfer->getIdShipmentMethod());
        }

        // Act
        $actualCartChangeTransfer = $this->tester->getFacade()->updateShipmentPrice($cartChangeTransfer);

        // Assert
        foreach ($actualCartChangeTransfer->getQuote()->getExpenses() as $expenseTransfer) {
            $shipmentMethodTransfer = $expenseTransfer->getShipment()->getMethod();
            $this->assertEquals(
                $expectedPrices[$shipmentMethodTransfer->getName()][$cartChangeTransfer->getQuote()->getCurrency()->getCode()],
                $expenseTransfer->getUnitNetPrice(),
                sprintf('Shipment price should have been changed for shipment %s.', $expenseTransfer->getName())
            );
        }
    }

    /**
     * @return array
     */
    public function updateShipmentPriceWithItemLevelShipment(): array
    {
        return [
            'quote has multi shipments, quote currency and shipment currency are the same; shipment prices should have been changed' => $this->getDataWithItemLevelShipmentAndQuteCurrencyIsNotSameAsShipmentMethodCurrency(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShipmentAndQuteCurrencyIsNotSameAsShipmentMethodCurrency(): array
    {
        $shipmentBuilder1 = (new ShipmentBuilder())
            ->withMethod(
                (new ShipmentMethodBuilder([ShipmentMethodTransfer::CURRENCY_ISO_CODE => static::CURRENCY_CODE_EUR]))
                    ->withPrice(
                        (new MoneyValueBuilder())
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                    )
                    ->withAnotherPrice(
                        (new MoneyValueBuilder())
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                    )
            );
        $shipmentBuilder2 = (new ShipmentBuilder())
            ->withAnotherMethod(
                (new ShipmentMethodBuilder([ShipmentMethodTransfer::CURRENCY_ISO_CODE => static::CURRENCY_CODE_EUR]))
                    ->withAnotherPrice(
                        (new MoneyValueBuilder())
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                    )
                    ->withAnotherPrice(
                        (new MoneyValueBuilder())
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                    )
            );

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote(
                (new QuoteBuilder([
                    QuoteTransfer::PRICE_MODE => ShipmentConstants::PRICE_MODE_NET,
                ]))
                    ->withItem(
                        (new ItemBuilder())
                            ->withShipment($shipmentBuilder1)
                    )
                    ->withAnotherItem(
                        (new ItemBuilder())
                            ->withShipment($shipmentBuilder2)
                    )
                    ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                ->withExpense(
                    (new ExpenseBuilder())->withShipment($shipmentBuilder1)
                )
                ->withAnotherExpense(
                    (new ExpenseBuilder())->withShipment($shipmentBuilder2)
                )
            )
            ->build();

        $expectedPrices = [];
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getShipment()->getMethod()->getPrices() as $moneyValueTransfer) {
                $expectedPrices[$itemTransfer->getShipment()->getMethod()->getName()] = [$moneyValueTransfer->getCurrency()->getCode() => $moneyValueTransfer->getNetAmount()];
            }
        }

        return [$cartChangeTransfer, $expectedPrices];
    }
}
