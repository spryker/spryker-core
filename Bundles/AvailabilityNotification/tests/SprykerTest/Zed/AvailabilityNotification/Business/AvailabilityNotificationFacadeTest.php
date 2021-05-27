<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group Facade
 * @group AvailabilityNotificationFacadeTest
 * Add your own group annotations below this line
 */
class AvailabilityNotificationFacadeTest extends Unit
{
    public const TESTER_INVALID_EMAIL = 'invalid<>example@spryker.com';

    public const TESTER_INCORRECT_SUBSCRIPTION_KEY = '992233445566778899';

    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester|\SprykerTest\Zed\AvailabilityNotification\Helper\AvailabilityNotificationDataHelper|\SprykerTest\Shared\Product\Helper\ProductDataHelper|\SprykerTest\Shared\Customer\Helper\CustomerDataHelper
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected $availabilityNotificationFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->availabilityNotificationFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testGuestSubscribeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct()
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCustomerSubscribeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $this->tester->haveCustomer()
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeFailsWhenEmailIsInvalid(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            null,
            [
                'email' => static::TESTER_INVALID_EMAIL,
            ]
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeForAlreadySubscribedTypeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct()
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeBySubscriptionKeyShouldSucceed(): void
    {
        $this->mockMailDependency();

        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscription(
            $this->tester->haveProduct()
        );

        $response = $this->availabilityNotificationFacade->unsubscribeBySubscriptionKey($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeByCustomerReferenceAndSkuShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $this->tester->haveCustomer()
        );

        $this->mockMailDependency();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $response = $this->availabilityNotificationFacade->unsubscribeByCustomerReferenceAndSku($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeWithIncorrectSubscriptionKeyShouldFail(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            null,
            [
                'subscription_key' => static::TESTER_INCORRECT_SUBSCRIPTION_KEY,
            ]
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->unsubscribeBySubscriptionKey($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testAnonymize(): void
    {
        $customer = $this->tester->haveCustomer();
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $customer
        );

        $this->mockMailDependency();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->availabilityNotificationFacade->anonymizeSubscription($customer);

        $result = $this->tester
            ->findAvailabilityNotificationByCustomerReferenceAndSku(
                $customer->getCustomerReference(),
                $availabilityNotificationSubscription->getSku(),
                $availabilityNotificationSubscription->getStore()->getIdStore()
            );

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testExpandCustomerTransferWithAvailabilityNotificationSubscriptionList(): void
    {
        $product1 = $this->tester->haveProduct();
        $product2 = $this->tester->haveProduct();
        $customer = $this->tester->haveCustomer();
        $this->mockMailDependency();
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product1,
                $customer
            )
        );
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product2,
                $customer
            )
        );
        $expandedCustomerTransfer = $this->availabilityNotificationFacade->expandCustomerTransferWithAvailabilityNotificationSubscriptionList($customer);
        $this->assertEmpty(
            array_diff(
                [
                    $product1->getSku(),
                    $product2->getSku(),
                ],
                $expandedCustomerTransfer->getAvailabilityNotificationSubscriptionSkus()
            )
        );
    }

    /**
     * @return void
     */
    public function testGetAvailabilityNotifications(): void
    {
        $product1 = $this->tester->haveProduct();
        $product2 = $this->tester->haveProduct();
        $customer = $this->tester->haveCustomer();
        $this->mockMailDependency();
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product1,
                $customer
            )
        );
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product2,
                $customer
            )
        );
        $availabilityNotificationSubscriptionCollectionTransfer = $this->availabilityNotificationFacade->getAvailabilityNotifications(
            (new AvailabilityNotificationCriteriaTransfer())->addCustomerReference($customer->getCustomerReference())
        );
        $this->assertEquals(2, $availabilityNotificationSubscriptionCollectionTransfer->getAvailabilityNotificationSubscriptions()->count());
    }

    /**
     * @return void
     */
    protected function mockMailDependency(): void
    {
        $this->tester
            ->setDependency(
                AvailabilityNotificationDependencyProvider::FACADE_MAIL,
                $this->createMock(AvailabilityNotificationToMailFacadeInterface::class)
            );
    }
}
