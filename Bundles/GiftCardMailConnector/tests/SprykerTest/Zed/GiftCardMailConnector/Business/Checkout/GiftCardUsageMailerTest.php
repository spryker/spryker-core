<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCardMailConnector\Business\Checkout;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\GiftCardMailConnector\Business\Checkout\GiftCardUsageMailer;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface;
use Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface;
use SprykerTest\Zed\GiftCardMailConnector\GiftCardMailConnectorBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCardMailConnector
 * @group Business
 * @group Checkout
 * @group GiftCardUsageMailerTest
 * Add your own group annotations below this line
 */
class GiftCardUsageMailerTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GiftCardMailConnector\GiftCardMailConnectorBusinessTester
     */
    protected GiftCardMailConnectorBusinessTester $tester;

    /**
     * @return void
     */
    public function testSendUsageNotificationExpandsMailTransferWithStoreName(): void
    {
        // Arrange
        $quoteTransfer = $this->tester->haveQuoteWithReleations();

        // Assert
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock->expects($this->once())
            ->method('handleMail')
            ->willReturnCallback(function (MailTransfer $mailTransfer) use ($quoteTransfer) {
                $this->assertSame($quoteTransfer->getStore()->getName(), $mailTransfer->getStoreName());
            });

        // Act
        $giftCardUsageMailer = new GiftCardUsageMailer(
            $mailFacadeMock,
            $this->createGiftCardFacadeMock($quoteTransfer),
        );
        $giftCardUsageMailer->sendUsageNotification($quoteTransfer, new CheckoutResponseTransfer());
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToMailFacadeInterface
     */
    protected function createMailFacadeMock(): GiftCardMailConnectorToMailFacadeInterface
    {
        return $this->createMock(GiftCardMailConnectorToMailFacadeInterface::class);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\GiftCardMailConnector\Dependency\Facade\GiftCardMailConnectorToGiftCardFacadeInterface
     */
    protected function createGiftCardFacadeMock(QuoteTransfer $quoteTransfer): GiftCardMailConnectorToGiftCardFacadeInterface
    {
        $giftCardFacadeMock = $this->createMock(GiftCardMailConnectorToGiftCardFacadeInterface::class);
        $giftCardFacadeMock->expects($this->exactly(count($quoteTransfer->getGiftCards())))
            ->method('findById')
            ->willReturn($this->tester->haveGiftCard());

        return $giftCardFacadeMock;
    }
}
