<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardPaymentSaverPluginInterface;
use Spryker\Zed\GiftCard\GiftCardConfig;
use Spryker\Zed\GiftCard\GiftCardDependencyProvider;
use SprykerTest\Zed\GiftCard\GiftCardBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group CreateGiftCardPaymentsFromQuoteTest
 * Add your own group annotations below this line
 */
class CreateGiftCardPaymentsFromQuoteTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_GIFT_CARD_CODE = 'testCode';

    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected GiftCardBusinessTester $tester;

    /**
     * @return void
     */
    public function testCreatesPaymentGiftCardEntity(): void
    {
        // Arrange
        $idSalesPayment = $this->tester->createSalesPaymentEntity();
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setGiftCard((new GiftCardTransfer())->setCode(static::TEST_GIFT_CARD_CODE))
            ->setAmount(100)
            ->setIdSalesPayment($idSalesPayment);
        $quoteTransfer = (new QuoteTransfer())
            ->addPayment(new PaymentTransfer())
            ->addPayment($paymentTransfer);

        // Act
        $this->tester->getFacade()->createGiftCardPaymentsFromQuote($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment, 1);
        $this->tester->assertPaymentGiftCardExistBySalesPaymentIdAndCode($idSalesPayment, static::TEST_GIFT_CARD_CODE);
    }

    /**
     * @return void
     */
    public function testDoesNotCreatePaymentGiftCardEntityWhenPaymentGiftCardIsNotSet(): void
    {
        // Arrange
        $idSalesPayment = $this->tester->createSalesPaymentEntity();
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setAmount(100)
            ->setIdSalesPayment($idSalesPayment);
        $quoteTransfer = (new QuoteTransfer())
            ->addPayment(new PaymentTransfer())
            ->addPayment($paymentTransfer);

        // Act
        $this->tester->getFacade()->createGiftCardPaymentsFromQuote($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment, 0);
    }

    /**
     * @return void
     */
    public function testDoesNotCreatePaymentGiftCardEntityWhenPaymentAmountIsNotSet(): void
    {
        // Arrange
        $idSalesPayment = $this->tester->createSalesPaymentEntity();
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setGiftCard((new GiftCardTransfer())->setCode(static::TEST_GIFT_CARD_CODE))
            ->setIdSalesPayment($idSalesPayment);
        $quoteTransfer = (new QuoteTransfer())
            ->addPayment(new PaymentTransfer())
            ->addPayment($paymentTransfer);

        // Act
        $this->tester->getFacade()->createGiftCardPaymentsFromQuote($quoteTransfer, new SaveOrderTransfer());

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment, 0);
    }

    /**
     * @return void
     */
    public function testExecutesGiftCardPaymentSaverPluginStack(): void
    {
        // Arrange
        $paymentTransfer = (new PaymentTransfer())
            ->setPaymentProvider(GiftCardConfig::PROVIDER_NAME)
            ->setGiftCard((new GiftCardTransfer())->setCode(static::TEST_GIFT_CARD_CODE))
            ->setAmount(100)
            ->setIdSalesPayment($this->tester->createSalesPaymentEntity());
        $quoteTransfer = (new QuoteTransfer())->addPayment($paymentTransfer);
        $giftCardPaymentSaverPluginMock = $this->getMockBuilder(GiftCardPaymentSaverPluginInterface::class)
            ->getMock();

        // Assert
        $giftCardPaymentSaverPluginMock->expects($this->once())->method('savePayment');
        $this->tester->setDependency(
            GiftCardDependencyProvider::GIFT_CARD_PAYMENT_SAVER_PLUGINS,
            [
                $giftCardPaymentSaverPluginMock,
            ],
        );

        // Act
        $this->tester->getFacade()->createGiftCardPaymentsFromQuote($quoteTransfer, new SaveOrderTransfer());
    }
}
