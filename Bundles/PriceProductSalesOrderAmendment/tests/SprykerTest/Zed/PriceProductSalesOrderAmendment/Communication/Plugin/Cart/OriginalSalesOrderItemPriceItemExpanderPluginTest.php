<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Cart;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\NullValueException;
use Spryker\Zed\PriceProductSalesOrderAmendment\Communication\Plugin\Cart\OriginalSalesOrderItemPriceItemExpanderPlugin;
use SprykerTest\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PriceProductSalesOrderAmendment
 * @group Communication
 * @group Plugin
 * @group Cart
 * @group OriginalSalesOrderItemPriceItemExpanderPluginTest
 * Add your own group annotations below this line
 */
class OriginalSalesOrderItemPriceItemExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @var \SprykerTest\Zed\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentCommunicationTester
     */
    protected PriceProductSalesOrderAmendmentCommunicationTester $tester;

    /**
     * @return void
     */
    public function testShouldDoNothingWhenAmendmentOrderReferenceIsNotSet(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setAmendmentOrderReference(null)
            ->setOriginalSalesOrderItemUnitPrices([
                'sku1' => 200,
            ]);
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($quoteTransfer)
            ->addItem((new ItemTransfer())
                ->setSku('sku1')
                ->setUnitGrossPrice(100)
                ->setUnitNetPrice(100)
                ->setPriceProduct((new PriceProductTransfer())->setMoneyValue((new MoneyValueTransfer())->setGrossAmount(100))));

        // Act
        $cartChangeTransfer = (new OriginalSalesOrderItemPriceItemExpanderPlugin())->expandItems($cartChangeTransfer);

        // Assert
        $this->assertSame(100, $cartChangeTransfer->getItems()[0]->getUnitGrossPrice());
        $this->assertSame(100, $cartChangeTransfer->getItems()[0]->getUnitNetPrice());
    }

    /**
     * @dataProvider shouldThrowNullValueExceptionDataProvider
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param string $exceptionMessage
     *
     * @return void
     */
    public function testShouldThrowNullValueException(
        CartChangeTransfer $cartChangeTransfer,
        string $exceptionMessage
    ): void {
        // Assert
        $this->expectException(NullValueException::class);
        $this->expectExceptionMessage($exceptionMessage);

        // Act
        (new OriginalSalesOrderItemPriceItemExpanderPlugin())->expandItems($cartChangeTransfer);
    }

    /**
     * @return void
     */
    public function testShouldDoNothingWhenOriginalSalesOrderItemPriceIsNotFound(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($this->createQuoteTransferWithAmendedOrderReference()->setOriginalSalesOrderItemUnitPrices([]))
            ->addItem(
                (new ItemTransfer())->setSku('sku1')
                    ->setUnitNetPrice(100)
                    ->setPriceProduct((new PriceProductTransfer())->setMoneyValue((new MoneyValueTransfer())->setNetAmount(100))),
            );

        // Act
        $expandedCartChangeTransfer = (new OriginalSalesOrderItemPriceItemExpanderPlugin())
            ->expandItems($cartChangeTransfer);

        // Assert
        $this->assertSame(100, $expandedCartChangeTransfer->getItems()[0]->getUnitNetPrice());
        $this->assertSame(100, $expandedCartChangeTransfer->getItems()[0]->getPriceProduct()->getMoneyValue()->getNetAmount());
    }

    /**
     * @return void
     */
    public function testShouldReplaceDefaultPriceWhenOriginalSalesOrderItemPriceIsLowerThenDefaultPrice(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote(
                $this->createQuoteTransferWithAmendedOrderReference()->setOriginalSalesOrderItemUnitPrices([
                    'sku1' => 50,
                ]),
            )->addItem(
                (new ItemTransfer())->setSku('sku1')
                    ->setUnitNetPrice(100)
                    ->setPriceProduct((new PriceProductTransfer())->setMoneyValue((new MoneyValueTransfer())->setNetAmount(100))),
            );

        // Act
        $expandedCartChangeTransfer = (new OriginalSalesOrderItemPriceItemExpanderPlugin())
            ->expandItems($cartChangeTransfer);

        // Assert
        $this->assertSame(50, $expandedCartChangeTransfer->getItems()[0]->getUnitNetPrice());
        $this->assertSame(50, $expandedCartChangeTransfer->getItems()[0]->getPriceProduct()->getMoneyValue()->getNetAmount());
    }

    /**
     * @return void
     */
    public function testShouldNotReplaceDefaultPriceWhenOriginalSalesOrderItemPriceIsHigherThenDefaultPrice(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setQuote($this->createQuoteTransferWithAmendedOrderReference()->setOriginalSalesOrderItemUnitPrices([
                'sku1' => 150,
            ]))->addItem(
                (new ItemTransfer())->setSku('sku1')
                    ->setUnitNetPrice(100)
                    ->setPriceProduct((new PriceProductTransfer())->setMoneyValue((new MoneyValueTransfer())->setNetAmount(100))),
            );

        // Act
        $expandedCartChangeTransfer = (new OriginalSalesOrderItemPriceItemExpanderPlugin())
            ->expandItems($cartChangeTransfer);

        // Assert
        $this->assertSame(100, $expandedCartChangeTransfer->getItems()[0]->getUnitNetPrice());
        $this->assertSame(100, $expandedCartChangeTransfer->getItems()[0]->getPriceProduct()->getMoneyValue()->getNetAmount());
    }

    /**
     * @return array<string, list<\Generated\Shared\Transfer\CartChangeTransfer|string>>
     */
    protected function shouldThrowNullValueExceptionDataProvider(): array
    {
        return [
            'CartChangeTransfer.quote is not set' => [
                new CartChangeTransfer(),
                'Property "quote" of transfer `Generated\Shared\Transfer\CartChangeTransfer` is null.',
            ],
            'CartChangeTransfer.items.sku is not set' => [
                (new CartChangeTransfer())
                    ->setQuote($this->createQuoteTransferWithAmendedOrderReference())
                    ->addItem((new ItemTransfer())),
                'Property "sku" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.',
            ],
            'CartChangeTransfer.quote.priceMode is not set' => [
                (new CartChangeTransfer())
                    ->setQuote((new QuoteTransfer())->setPriceMode(null)->setOriginalSalesOrderItemUnitPrices(['sku1' => 50]))
                    ->addItem(
                        (new ItemTransfer())->setSku('sku1')->setUnitNetPrice(100)->setPriceProduct(new PriceProductTransfer()),
                    ),
                'Property "priceMode" of transfer `Generated\Shared\Transfer\QuoteTransfer` is null.',
            ],
            'CartChangeTransfer.items.priceProduct is not set' => [
                (new CartChangeTransfer())
                    ->setQuote(
                        $this->createQuoteTransferWithAmendedOrderReference()->setOriginalSalesOrderItemUnitPrices(['sku1' => 50]),
                    )->addItem((new ItemTransfer())->setSku('sku1')->setUnitNetPrice(100)),
                'Property "priceProduct" of transfer `Generated\Shared\Transfer\ItemTransfer` is null.',
            ],
            'CartChangeTransfer.items.priceProduct.moneyValue is not set' => [
                (new CartChangeTransfer())
                    ->setQuote(
                        $this->createQuoteTransferWithAmendedOrderReference()->setOriginalSalesOrderItemUnitPrices(['sku1' => 50]),
                    )->addItem(
                        (new ItemTransfer())->setSku('sku1')->setUnitNetPrice(100)->setPriceProduct(new PriceProductTransfer()),
                    ),
                'Property "moneyValue" of transfer `Generated\Shared\Transfer\PriceProductTransfer` is null.',
            ],
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function createQuoteTransferWithAmendedOrderReference(): QuoteTransfer
    {
        return (new QuoteTransfer())
            ->setPriceMode(static::PRICE_MODE_NET)
            ->setAmendmentOrderReference('test');
    }
}
