<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business\Notification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Notification\AvailabilityNotificationUnsubscriptionSender;
use Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group Notification
 * @group AvailabilityNotificationUnsubscriptionSenderTest
 * Add your own group annotations below this line
 */
class AvailabilityNotificationUnsubscriptionSenderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester
     */
    protected AvailabilityNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testSendNotificationSendsMailTransferExpandedWithStoreName(): void
    {
        //Arrange
        $availabilityNotificationSubscriptionTransfer = $this->tester
            ->haveAvailabilityNotificationSubscriptionTransfer($this->tester->haveProduct())
            ->setStore($this->tester->haveStore())
            ->setLocale($this->tester->haveLocale());

        //Assert
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock
            ->expects($this->once())
            ->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) use ($availabilityNotificationSubscriptionTransfer) {
                return $mailTransfer->getStoreName() === $availabilityNotificationSubscriptionTransfer->getStore()->getName();
            }));

        //Act
        $availabilityNotificationUnsubscriptionSender = new AvailabilityNotificationUnsubscriptionSender(
            $mailFacadeMock,
            $this->createProductFacadeMock(),
            $this->createUrlGeneratorMock(),
            $this->createProductAttributeFinderMock(),
        );
        $availabilityNotificationUnsubscriptionSender->send($availabilityNotificationSubscriptionTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface
     */
    protected function createMailFacadeMock(): AvailabilityNotificationToMailFacadeInterface
    {
        return $this->createMock(AvailabilityNotificationToMailFacadeInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface
     */
    protected function createProductFacadeMock(): AvailabilityNotificationToProductFacadeInterface
    {
        $productFacadeMock = $this->createMock(AvailabilityNotificationToProductFacadeInterface::class);
        $productFacadeMock->expects($this->once())
            ->method('getProductConcrete')
            ->willReturn($this->tester->haveProduct());

        return $productFacadeMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface
     */
    protected function createUrlGeneratorMock(): UrlGeneratorInterface
    {
        return $this->createMock(UrlGeneratorInterface::class);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface
     */
    protected function createProductAttributeFinderMock(): ProductAttributeFinderInterface
    {
        return $this->createMock(ProductAttributeFinderInterface::class);
    }
}
