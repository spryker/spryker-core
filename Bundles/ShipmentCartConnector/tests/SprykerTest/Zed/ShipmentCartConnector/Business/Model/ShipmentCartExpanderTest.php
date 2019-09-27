<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ShipmentCartConnector\Business\Model;

use Codeception\TestCase\Test;
use Generated\Shared\DataBuilder\CartChangeBuilder;
use Generated\Shared\DataBuilder\ExpenseBuilder;
use Generated\Shared\DataBuilder\MoneyValueBuilder;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\DataBuilder\ShipmentBuilder;
use Generated\Shared\DataBuilder\ShipmentMethodBuilder;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ExpenseTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\Shipment\ShipmentConstants;
use Spryker\Shared\ShipmentCartConnector\ShipmentCartConnectorConfig;

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
     * @dataProvider updateShipmentPriceWithoutQuoteLevelShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param int $expectedPrice
     *
     * @return void
     */
    public function testUpdateShipmentPriceWithoutQuoteLevelShipment(
        CartChangeTransfer $cartChangeTransfer,
        int $expectedPrice
    ): void {
        // Arrange
        $cartChangeTransfer = $this->haveAvailableShipmentMethods($cartChangeTransfer);

        // Act
        $actualCartChangeTransfer = $this->tester->getFacade()->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertNull(
            $actualCartChangeTransfer->getQuote()->getShipment(),
            'Quote shipment should not have been set.'
        );

        $this->assertEquals(
            $expectedPrice,
            $actualCartChangeTransfer->getQuote()->getExpenses()[0]->getUnitNetPrice(),
            sprintf('Shipment price should not have been changed for shipment expense.')
        );
    }

    /**
     * @return array
     */
    public function updateShipmentPriceWithoutQuoteLevelShipmentDataProvider(): array
    {
        return [
            'quote has not shipment method; shipment price should not have been changed' => $this->getDataWithoutShipment(),
        ];
    }

    /**
     * @dataProvider updateShipmentPriceWithQuoteLevelShipmentDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param int $expectedPrice
     *
     * @return void
     */
    public function testUpdateShipmentPriceWithQuoteLevelShipment(
        CartChangeTransfer $cartChangeTransfer,
        int $expectedPrice
    ): void {
        // Arrange
        $cartChangeTransfer = $this->haveAvailableShipmentMethods($cartChangeTransfer);

        // Act
        $actualCartChangeTransfer = $this->tester->getFacade()->updateShipmentPrice($cartChangeTransfer);

        // Assert
        $this->assertSame(
            $cartChangeTransfer->getQuote()->getShipment()->getMethod()->getPrices(),
            $actualCartChangeTransfer->getQuote()->getShipment()->getMethod()->getPrices(),
            'Shipment price should not have been changed.'
        );

        $this->assertEquals(
            $expectedPrice,
            $actualCartChangeTransfer->getQuote()->getExpenses()[0]->getUnitNetPrice(),
            sprintf('Shipment price should not have been changed for shipment expense.')
        );
    }

    /**
     * @return array
     */
    public function updateShipmentPriceWithQuoteLevelShipmentDataProvider(): array
    {
        return [
            'quote currency and shipment currency are the same; shipment price should not have been changed' => $this->getDataWithQuoteLevelShipmentWhereQuoteCurrencyAndShipmentCurrencyAreSame(),
        ];
    }

    /**
     * @return array
     */
    public function getDataWithoutShipment(): array
    {
        $netPriceAmountEur1 = 10000;

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote(
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => ShipmentConstants::PRICE_MODE_NET]))
                    ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                    ->withExpense(
                        (new ExpenseBuilder([
                            ExpenseTransfer::TYPE => ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE,
                            ExpenseTransfer::UNIT_PRICE => $netPriceAmountEur1,
                            ExpenseTransfer::UNIT_NET_PRICE => $netPriceAmountEur1,
                        ]))
                    )
            )
            ->build();

        $quoteTransfer = $cartChangeTransfer->getQuote();
        $quoteTransfer->setShipment(null);
        $quoteTransfer->getExpenses()[0]->setShipment(null);

        return [$cartChangeTransfer, $netPriceAmountEur1];
    }

    /**
     * @return array
     */
    public function getDataWithQuoteLevelShipmentWhereQuoteCurrencyAndShipmentCurrencyAreSame(): array
    {
        $netPriceAmountEur1 = 10000;
        $netPriceAmountUsd1 = 20000;

        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withMethod(
                (new ShipmentMethodBuilder([ShipmentMethodTransfer::CURRENCY_ISO_CODE => static::CURRENCY_CODE_EUR]))
                    ->withPrice(
                        (new MoneyValueBuilder([MoneyValueTransfer::NET_AMOUNT => $netPriceAmountEur1]))
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                            ->withStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME])
                    )
                    ->withAnotherPrice(
                        (new MoneyValueBuilder([MoneyValueTransfer::NET_AMOUNT => $netPriceAmountUsd1]))
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                            ->withStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME])
                    )
            )->build();

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote(
                (new QuoteBuilder([QuoteTransfer::PRICE_MODE => ShipmentConstants::PRICE_MODE_NET]))
                    ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                    ->withExpense(
                        (new ExpenseBuilder([
                            ExpenseTransfer::TYPE => ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE,
                            ExpenseTransfer::UNIT_PRICE => $netPriceAmountEur1,
                            ExpenseTransfer::UNIT_NET_PRICE => $netPriceAmountEur1,
                        ]))
                    )
            )
            ->build();

        $quoteTransfer = $cartChangeTransfer->getQuote();
        $quoteTransfer->setShipment($shipmentTransfer1);
        $quoteTransfer->getExpenses()[0]->setShipment($shipmentTransfer1);

        return [$cartChangeTransfer, $netPriceAmountEur1];
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
        $cartChangeTransfer = $this->haveAvailableShipmentMethods($cartChangeTransfer);

        // Act
        $actualCartChangeTransfer = $this->tester->getFacade()->updateShipmentPrice($cartChangeTransfer);

        // Assert
        foreach ($actualCartChangeTransfer->getQuote()->getExpenses() as $i => $expenseTransfer) {
            $shipmentMethodTransfer = $expenseTransfer->getShipment()->getMethod();
            $this->assertEquals(
                $expectedPrices[$shipmentMethodTransfer->getName()][$cartChangeTransfer->getQuote()->getCurrency()->getCode()],
                $expenseTransfer->getUnitNetPrice(),
                sprintf('Shipment price should have been changed for shipment expense #%s.', $i)
            );
        }
    }

    /**
     * @return array
     */
    public function updateShipmentPriceWithItemLevelShipment(): array
    {
        return [
            'quote has multi shipments, quote currency and shipment currency are the same; shipment prices should have been changed' => $this->getDataWithItemLevelShipmentAndQuoteCurrencyIsNotSameAsShipmentMethodCurrency(),
        ];
    }

    /**
     * @return array
     */
    protected function getDataWithItemLevelShipmentAndQuoteCurrencyIsNotSameAsShipmentMethodCurrency(): array
    {
        $netPriceAmountEur1 = 10000;
        $netPriceAmountUsd1 = 20000;
        $netPriceAmountEur2 = 15000;
        $netPriceAmountUsd2 = 30000;

        $shipmentTransfer1 = (new ShipmentBuilder())
            ->withMethod(
                (new ShipmentMethodBuilder([ShipmentMethodTransfer::CURRENCY_ISO_CODE => static::CURRENCY_CODE_EUR]))
                    ->withPrice(
                        (new MoneyValueBuilder([MoneyValueTransfer::NET_AMOUNT => $netPriceAmountEur1]))
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                            ->withStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME])
                    )
                    ->withAnotherPrice(
                        (new MoneyValueBuilder([MoneyValueTransfer::NET_AMOUNT => $netPriceAmountUsd1]))
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                            ->withStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME])
                    )
            )->build();
        $shipmentTransfer2 = (new ShipmentBuilder())
            ->withAnotherMethod(
                (new ShipmentMethodBuilder([ShipmentMethodTransfer::CURRENCY_ISO_CODE => static::CURRENCY_CODE_EUR]))
                    ->withAnotherPrice(
                        (new MoneyValueBuilder([MoneyValueTransfer::NET_AMOUNT => $netPriceAmountEur2]))
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_EUR])
                            ->withStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME])
                    )
                    ->withAnotherPrice(
                        (new MoneyValueBuilder([MoneyValueTransfer::NET_AMOUNT => $netPriceAmountUsd2]))
                            ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                            ->withStore([StoreTransfer::NAME => static::DEFAULT_STORE_NAME])
                    )
            )->build();

        $cartChangeTransfer = (new CartChangeBuilder())
            ->withQuote(
                (new QuoteBuilder([
                    QuoteTransfer::PRICE_MODE => ShipmentConstants::PRICE_MODE_NET,
                ]))
                    ->withItem()
                    ->withAnotherItem()
                    ->withCurrency([CurrencyTransfer::CODE => static::CURRENCY_CODE_USD])
                ->withExpense(
                    (new ExpenseBuilder([
                        ExpenseTransfer::TYPE => ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE,
                        ExpenseTransfer::UNIT_PRICE => $netPriceAmountEur1,
                        ExpenseTransfer::UNIT_NET_PRICE => $netPriceAmountEur1,
                    ]))
                )
                ->withAnotherExpense(
                    (new ExpenseBuilder([
                        ExpenseTransfer::TYPE => ShipmentCartConnectorConfig::SHIPMENT_EXPENSE_TYPE,
                        ExpenseTransfer::UNIT_PRICE => $netPriceAmountEur2,
                        ExpenseTransfer::UNIT_NET_PRICE => $netPriceAmountEur2,
                    ]))
                )
            )
            ->build();

        $quoteTransfer = $cartChangeTransfer->getQuote();
        $quoteTransfer->getItems()[0]->setShipment($shipmentTransfer1);
        $quoteTransfer->getExpenses()[0]->setShipment($shipmentTransfer1);
        $quoteTransfer->getItems()[1]->setShipment($shipmentTransfer2);
        $quoteTransfer->getExpenses()[1]->setShipment($shipmentTransfer2);

        $expectedPrices = [
            $shipmentTransfer1->getMethod()->getName() => [
                $shipmentTransfer1->getMethod()->getPrices()[0]->getCurrency()->getCode() => $shipmentTransfer1->getMethod()->getPrices()[0]->getNetAmount(),
                $shipmentTransfer1->getMethod()->getPrices()[1]->getCurrency()->getCode() => $shipmentTransfer1->getMethod()->getPrices()[1]->getNetAmount(),
            ],
            $shipmentTransfer2->getMethod()->getName() => [
                $shipmentTransfer2->getMethod()->getPrices()[0]->getCurrency()->getCode() => $shipmentTransfer2->getMethod()->getPrices()[0]->getNetAmount(),
                $shipmentTransfer2->getMethod()->getPrices()[1]->getCurrency()->getCode() => $shipmentTransfer2->getMethod()->getPrices()[1]->getNetAmount(),
            ],
        ];

        return [$cartChangeTransfer, $expectedPrices];
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    protected function haveAvailableShipmentMethods(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getQuote()->getExpenses() as $expenseTransfer) {
            if ($expenseTransfer->getShipment() === null
                || $expenseTransfer->getShipment()->getMethod() === null) {
                continue;
            }

            $shipmentData = [ShipmentMethodTransfer::NAME => $expenseTransfer->getShipment()->getMethod()->getName()];

            $shipmentTransfer = $this->tester
                ->haveShipmentMethod(
                    $shipmentData,
                    [],
                    $this->prepareShipmentMethodPriceListBuilderOptions($expenseTransfer->getShipment()->getMethod())
                );
            $expenseTransfer->getShipment()->getMethod()->setIdShipmentMethod($shipmentTransfer->getIdShipmentMethod());
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentMethodTransfer $shipmentMethodTransfer
     *
     * @return array
     */
    protected function prepareShipmentMethodPriceListBuilderOptions(ShipmentMethodTransfer $shipmentMethodTransfer): array
    {
        $options = [];

        foreach ($shipmentMethodTransfer->getPrices() as $moneyValueTransfer) {
            $storeName = $moneyValueTransfer->getStore()->getName();
            if (!isset($options[$storeName])) {
                $options[$storeName] = [];
            }

            $currencyIsoCode = $moneyValueTransfer->getCurrency()->getCode();
            $options[$storeName][$currencyIsoCode] = $moneyValueTransfer->modifiedToArray();
        }

        return $options;
    }
}
