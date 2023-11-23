<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CurrencyBuilder;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;
use Spryker\Zed\GiftCard\GiftCardDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use SprykerTest\Zed\GiftCard\GiftCardBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group RecalculationTest
 * Add your own group annotations below this line
 */
class RecalculationTest extends Unit
{
    /**
     * @var int
     */
    public const GIFT_CARD_AVAILABLE_AMOUNT = 800;

    /**
     * @var int
     */
    protected const GIFT_CARD_VALUE = 1000;

    /**
     * @var string
     */
    protected const DEFAULT_OMS_PROCESS_NAME = 'Test01';

    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected GiftCardBusinessTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->configureTestStateMachine([static::DEFAULT_OMS_PROCESS_NAME]);
    }

    /**
     * @return void
     */
    public function testUpdatesActualAmountOfGiftCardPaymentWhenGiftCardActualAmountHasChanged(): void
    {
        // Arrange
        $this->tester->setDependency(
            GiftCardDependencyProvider::GIFT_CARD_VALUE_PROVIDER,
            new class extends AbstractPlugin implements GiftCardValueProviderPluginInterface {
                /**
                 * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
                 *
                 * @return int
                 */
                public function getValue(GiftCardTransfer $giftCardTransfer): int
                {
                    return RecalculationTest::GIFT_CARD_AVAILABLE_AMOUNT;
                }
            },
        );

        $currencyTransfer = (new CurrencyBuilder())->seed([
            CurrencyTransfer::CODE => 'EUR',
        ])->build();
        $giftCardTransfer = $this->tester->haveGiftCard([
            GiftCardTransfer::IS_ACTIVE => true,
            GiftCardTransfer::VALUE => static::GIFT_CARD_VALUE,
            GiftCardTransfer::CURRENCY_ISO_CODE => $currencyTransfer->getCode(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer);
        $paymentTransfer = (new PaymentTransfer())
            ->setAmount($giftCardTransfer->getValue())
            ->setGiftCard($giftCardTransfer);
        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->addPayment($paymentTransfer)
            ->setOriginalQuote($quoteTransfer)
            ->addGiftCard($giftCardTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertSame(
            static::GIFT_CARD_AVAILABLE_AMOUNT,
            $paymentTransfer->getAvailableAmount(),
        );
    }

    /**
     * @return void
     */
    public function testUpdatesActualAmountOfGiftCardPaymentWhenGiftCardActualAmountEqualsItsValue(): void
    {
        // Arrange
        $currencyTransfer = (new CurrencyBuilder())->seed([
            CurrencyTransfer::CODE => 'EUR',
        ])->build();
        $giftCardTransfer = $this->tester->haveGiftCard([
            GiftCardTransfer::IS_ACTIVE => true,
            GiftCardTransfer::VALUE => static::GIFT_CARD_VALUE,
            GiftCardTransfer::CURRENCY_ISO_CODE => $currencyTransfer->getCode(),
        ]);

        $quoteTransfer = (new QuoteTransfer())
            ->setCurrency($currencyTransfer);
        $paymentTransfer = (new PaymentTransfer())
            ->setAmount($giftCardTransfer->getValue())
            ->setGiftCard($giftCardTransfer);
        $calculableObjectTransfer = (new CalculableObjectTransfer())
            ->addPayment($paymentTransfer)
            ->setOriginalQuote($quoteTransfer)
            ->addGiftCard($giftCardTransfer);

        // Act
        $this->tester->getFacade()->recalculate($calculableObjectTransfer);

        // Assert
        $this->assertSame(static::GIFT_CARD_VALUE, $paymentTransfer->getAvailableAmount());
    }
}
