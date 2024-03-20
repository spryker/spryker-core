<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business\CustomerExpander;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\CustomerExpander\CustomerExpander;
use Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;
use SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group CustomerExpander
 * @group CustomerExpanderTest
 * Add your own group annotations below this line
 */
class CustomerExpanderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester
     */
    protected AvailabilityNotificationBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandsCustomerTransferGetsCurrentStoreIfCustomerStoreNameIsNotProvided(): void
    {
        //Arrange
        $storeFacadeMock = $this->mockStoreFacade();
        $customerTransfer = $this->tester
            ->haveCustomer();

        //Assert
        $storeFacadeMock
            ->expects($this->once())
            ->method('getCurrentStore');
        $this->assertNull($customerTransfer->getStoreName());

        //Act
        $customerExpander = new CustomerExpander(
            $this->createAvailabilityNotificationSubscriptionReaderMock($customerTransfer),
            $storeFacadeMock,
        );
        $customerExpander->expandCustomerTransferWithAvailabilityNotificationSubscriptionList($customerTransfer);
    }

    /**
     * @return void
     */
    public function testExpandsCustomerTransferGetsCustomerStoreNameIfItIsProvided(): void
    {
        //Arrange
        $this->tester->haveStore([StoreTransfer::NAME => $this->tester::DEFAULT_STORE_NAME]);

        $customerTransfer = $this->tester
            ->haveCustomer()
            ->setStoreName($this->tester::DEFAULT_STORE_NAME);
        $storeFacadeMock = $this->mockStoreFacade();

        //Assert
        $storeFacadeMock->expects($this->never())
            ->method('getCurrentStore');

        //Act
        $customerExpander = new CustomerExpander(
            $this->createAvailabilityNotificationSubscriptionReaderMock($customerTransfer),
            $storeFacadeMock,
        );
        $customerExpander->expandCustomerTransferWithAvailabilityNotificationSubscriptionList($customerTransfer);
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected function mockStoreFacade(): AvailabilityNotificationToStoreFacadeInterface
    {
        return $this->createMock(AvailabilityNotificationToStoreFacadeInterface::class);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\AvailabilityNotification\Business\Subscription\AvailabilityNotificationSubscriptionReaderInterface
     */
    protected function createAvailabilityNotificationSubscriptionReaderMock(
        CustomerTransfer $customerTransfer
    ): AvailabilityNotificationSubscriptionReaderInterface {
        $this->setAvailabilityNotificationToMailFacadeDependency();

        $availabilityNotificationSubscriptionReaderMock = $this->createMock(AvailabilityNotificationSubscriptionReaderInterface::class);
        $availabilityNotificationSubscriptionReaderMock->expects($this->once())
            ->method('getAvailabilityNotifications')
            ->willReturn((new AvailabilityNotificationSubscriptionCollectionTransfer())
                ->addAvailabilityNotificationSubscription(
                    $this->tester->haveAvailabilityNotificationSubscription(
                        $this->tester->haveProduct(),
                        $customerTransfer,
                    ),
                ));

        return $availabilityNotificationSubscriptionReaderMock;
    }

    /**
     * @return void
     */
    protected function setAvailabilityNotificationToMailFacadeDependency(): void
    {
        $this->tester->setDependency(
            AvailabilityNotificationDependencyProvider::FACADE_MAIL,
            $this->createMock(AvailabilityNotificationToMailFacadeInterface::class),
        );
    }
}
