<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business\Notification;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\Business\Notification\ProductBecomeAvailableNotificationSender;
use Spryker\Zed\AvailabilityNotification\Business\Product\ProductAttributeFinderInterface;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\UrlGeneratorInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface;
use SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group Notification
 * @group ProductBecomeAvailableNotificationSenderTest
 * Add your own group annotations below this line
 */
class ProductBecomeAvailableNotificationSenderTest extends Unit
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
        $availabilityNotificationDataTransfer = $this->tester
            ->haveAvailabilityNotificationDataTransfer($this->tester->haveProduct())
            ->setStore($this->tester->haveStore());

        //Assert
        $mailFacadeMock = $this->createMailFacadeMock();
        $mailFacadeMock
            ->expects($this->once())
            ->method('handleMail')
            ->with($this->callback(function (MailTransfer $mailTransfer) use ($availabilityNotificationDataTransfer) {
                return $mailTransfer->getStoreName() === $availabilityNotificationDataTransfer->getStore()->getName();
            }));

        //Act
        $productBecomeAvailabilityNotificationSubscriptionSender = new ProductBecomeAvailableNotificationSender(
            $mailFacadeMock,
            $this->createProductFacadeMock(),
            $this->createUrlGeneratorMock(),
            $this->createAvailabilityNotificationRepositoryMock(),
            $this->createProductAttributeFinderMock(),
        );
        $productBecomeAvailabilityNotificationSubscriptionSender->send($availabilityNotificationDataTransfer);
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

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationRepositoryInterface
     */
    protected function createAvailabilityNotificationRepositoryMock(): AvailabilityNotificationRepositoryInterface
    {
        /*
         * Needed for haveAvailabilityNotificationSubscription helper method that uses facade that is called in Gateway context.
         */
        $this->tester->addCurrentStore($this->tester->haveStore([StoreTransfer::NAME => $this->tester::DEFAULT_STORE_NAME]));
        $availabilityNotificationRepositoryMock = $this->createMock(AvailabilityNotificationRepositoryInterface::class);
        $availabilityNotificationRepositoryMock->expects($this->once())
            ->method('getAvailabilityNotifications')
            ->willReturn((new AvailabilityNotificationSubscriptionCollectionTransfer())
                ->addAvailabilityNotificationSubscription(
                    $this->tester->haveAvailabilityNotificationSubscription(
                        $this->tester->haveProduct(),
                    ),
                ));
        $this->tester->removeCurrentStore();

        return $availabilityNotificationRepositoryMock;
    }
}
