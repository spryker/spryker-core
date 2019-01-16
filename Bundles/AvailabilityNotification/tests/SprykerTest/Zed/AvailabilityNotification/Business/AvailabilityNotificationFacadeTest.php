<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business;

use Codeception\Test\Unit;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationBusinessFactory;
use Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\Kernel\Container;

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
    public const TESTER_SUBSCRIPTION_KEY = '112233445566778899';

    public const TESTER_INCORRECT_SUBSCRIPTION_KEY = '992233445566778899';

    public const TESTER_PRODUCT_SKU = '001_25904006';

    public const TESTER_CUSTOMER_REFERENCE = 'DE--1';

    public const TESTER_INVALID_EMAIL = 'invalid<>example@spryker.com';

    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGuestSubscribeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscriptionTransfer([
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCustomerSubscribeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscriptionTransfer([
            'customer_reference' => static::TESTER_CUSTOMER_REFERENCE,
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeFailsWhenEmailIsInvalid()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscriptionTransfer([
            'email' => static::TESTER_INVALID_EMAIL,
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeForAlreadySubscribedTypeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscription([
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForAlreadySubscribedShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscription([
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $result = $this->getAvailabilityNotificationFacadeMock()->findAvailabilitySubscription($availabilityNotificationSubscription);

        $this->assertNotNull($result);
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForNotSubscribedShouldFail()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscriptionTransfer([
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $result = $this->getAvailabilityNotificationFacadeMock()->findAvailabilitySubscription($availabilityNotificationSubscription);

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testGuestUnsubscribeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscription([
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->unsubscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCustomerUnsubscribeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscription([
            'sku' => static::TESTER_PRODUCT_SKU,
            'customer_reference' => static::TESTER_CUSTOMER_REFERENCE,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->unsubscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeWithIncorrectSubscriptionKeyShouldFail()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscriptionTransfer([
            'subscription_key' => static::TESTER_INCORRECT_SUBSCRIPTION_KEY,
        ]);

        $response = $this->getAvailabilityNotificationFacadeMock()->unsubscribe($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testAnonymize()
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilitySubscription([
            'customerReference' => static::TESTER_CUSTOMER_REFERENCE,
            'sku' => static::TESTER_PRODUCT_SKU,
        ]);

        $customerTransfer = $this->tester->haveCustomerTransfer([
            'customerReference' => static::TESTER_CUSTOMER_REFERENCE,
        ]);

        $this->getAvailabilityNotificationFacadeMock()->anonymizeSubscription($customerTransfer);

        $result = $this->getAvailabilityNotificationFacadeMock()->findAvailabilitySubscription($availabilityNotificationSubscription);

        $this->assertNull($result);
    }

    /**
     * @return \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected function getAvailabilityNotificationFacadeMock(): AvailabilityNotificationFacade
    {
        $availabilityNotificationFacade = new AvailabilityNotificationFacade();
        $container = new Container();
        $availabilityNotificationDependencyProvider = new AvailabilityNotificationDependencyProvider();
        $availabilityNotificationDependencyProvider->provideBusinessLayerDependencies($container);

        $mailFacadeMock = $this->getMockBuilder(AvailabilityNotificationToMailFacadeInterface::class)->getMock();
        $container[AvailabilityNotificationDependencyProvider::FACADE_MAIL] = $mailFacadeMock;

        $availabilityNotificationBusinessFactory = new AvailabilityNotificationBusinessFactory();
        $availabilityNotificationBusinessFactory->setContainer($container);

        $availabilityNotificationFacade->setFactory($availabilityNotificationBusinessFactory);

        return $availabilityNotificationFacade;
    }
}
