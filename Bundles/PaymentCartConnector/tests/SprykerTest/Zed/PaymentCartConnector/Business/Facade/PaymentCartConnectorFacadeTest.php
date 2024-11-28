<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PaymentCartConnector\Business\Facade;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerTest\Zed\PaymentCartConnector\PaymentCartConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group PaymentCartConnector
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentCartConnectorFacadeTest
 * Add your own group annotations below this line
 */
class PaymentCartConnectorFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\PaymentAppShipment\PaymentAppShipmentBusinessTester
     */
    protected PaymentCartConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testRemoveQuotePaymentRemovesPayment(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setPayment(
            (new PaymentTransfer())->setPaymentMethod('method_to_remove'),
        );

        // Act
        $quoteTransfer = $this->tester->getFacade()->removeQuotePayment($quoteTransfer);

        // Assert
        $this->assertNull($quoteTransfer->getPayment());
    }

    /**
     * @return void
     */
    public function testRemoveQuotePaymentKeepsExcludedPayment(): void
    {
        // Arrange
        $excludedPaymentMethod = 'excluded_method';
        $this->tester->mockConfigMethod('getExcludedPaymentMethods', [$excludedPaymentMethod]);

        $quoteTransfer = (new QuoteTransfer())->setPayment(
            (new PaymentTransfer())->setPaymentMethod($excludedPaymentMethod),
        );

        // Act
        $quoteTransfer = $this->tester->getFacade()->removeQuotePayment($quoteTransfer);

        // Assert
        $this->assertNotNull($quoteTransfer->getPayment());
        $this->assertSame($excludedPaymentMethod, $quoteTransfer->getPayment()->getPaymentMethod());
    }

    /**
     * @return void
     */
    public function testRemoveQuotePaymentRemovesPayments(): void
    {
        // Arrange
        $quoteTransfer = (new QuoteTransfer())->setPayments(new ArrayObject([
            (new PaymentTransfer())->setPaymentMethod('method_to_remove_1'),
            (new PaymentTransfer())->setPaymentMethod('method_to_remove_2'),
        ]));

        // Act
        $quoteTransfer = $this->tester->getFacade()->removeQuotePayment($quoteTransfer);

        // Assert
        $this->assertCount(0, $quoteTransfer->getPayments());
    }

    /**
     * @return void
     */
    public function testRemoveQuotePaymentKeepsExcludedPayments(): void
    {
        // Arrange
        $excludedPaymentMethod = 'excluded_method';
        $this->tester->mockConfigMethod('getExcludedPaymentMethods', [$excludedPaymentMethod]);

        $quoteTransfer = (new QuoteTransfer())->setPayments(new ArrayObject([
            (new PaymentTransfer())->setPaymentMethod($excludedPaymentMethod),
            (new PaymentTransfer())->setPaymentMethod('method_to_remove'),
        ]));

        // Act
        $quoteTransfer = $this->tester->getFacade()->removeQuotePayment($quoteTransfer);

        // Assert
        $this->assertCount(1, $quoteTransfer->getPayments());
        $this->assertSame(
            $excludedPaymentMethod,
            $quoteTransfer->getPayments()->getIterator()->current()->getPaymentMethod(),
        );
    }

    /**
     * @return array<array<string>>
     */
    public function cartChangeOperationProvider(): array
    {
        return [
            'on cart add operation' => ['add'],
            'on cart add remove' => ['remove'],
        ];
    }

    /**
     * @dataProvider cartChangeOperationProvider
     *
     * @param string $operation
     *
     * @return void
     */
    public function testRemoveQuotePaymentOnCartChangeRemovesPayment(string $operation): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation($operation)
            ->setQuote(
                (new QuoteTransfer())->setPayment(
                    (new PaymentTransfer())->setPaymentMethod('method_to_remove'),
                )->setPayments(new ArrayObject([
                    (new PaymentTransfer())->setPaymentMethod('method_to_remove_1'),
                    (new PaymentTransfer())->setPaymentMethod('method_to_remove_2'),
                ])),
            );

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->removeQuotePaymentOnCartChange($cartChangeTransfer);

        // Assert
        $this->assertNull($cartChangeTransfer->getQuote()->getPayment());
        $this->assertCount(0, $cartChangeTransfer->getQuote()->getPayments());
    }

    /**
     * @return void
     */
    public function testRemoveQuotePaymentOnCartChangeDoesNotRemovePaymentWhenOperationIsEmpty(): void
    {
        // Arrange
        $cartChangeTransfer = (new CartChangeTransfer())
            ->setOperation('')
            ->setQuote(
                (new QuoteTransfer())->setPayment(
                    (new PaymentTransfer())->setPaymentMethod('method_to_keep'),
                )->setPayments(new ArrayObject([
                    (new PaymentTransfer())->setPaymentMethod('method_to_keep_1'),
                    (new PaymentTransfer())->setPaymentMethod('method_to_keep_2'),
                ])),
            );

        // Act
        $cartChangeTransfer = $this->tester->getFacade()->removeQuotePaymentOnCartChange($cartChangeTransfer);

        // Assert
        $this->assertNotNull($cartChangeTransfer->getQuote()->getPayment());
        $this->assertCount(2, $cartChangeTransfer->getQuote()->getPayments());
    }
}
