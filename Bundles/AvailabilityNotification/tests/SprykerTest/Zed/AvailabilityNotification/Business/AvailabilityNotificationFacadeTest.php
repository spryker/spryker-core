<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription;
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

    public const INCORRECT_TESTER_SUBSCRIPTION_KEY = '992233445566778899';

    public const TESTER_PRODUCT_SKU = '001_25904006';

    public const TESTER_CUSTOMER_REFERENCE = 'DE--1';

    public const TESTER_EMAIL = 'invalid<>example@spryker.com';

    public const TESTER_STORE = 1;

    public const TESTER_LOCALE = 66;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected $availabilityNotificationFacade;

    /**
     * @var \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilitySubscription
     */
    protected $availabilitySubscription;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setAvailabilityNotificationFacade();
        $this->setupAvailabilityNotification();
    }

    /**
     * @return void
     */
    protected function setupAvailabilityNotification()
    {
        $this->availabilitySubscription = (new SpyAvailabilitySubscription())
            ->setCustomerReference(static::TESTER_CUSTOMER_REFERENCE)
            ->setEmail(static::TESTER_EMAIL)
            ->setSku(static::TESTER_PRODUCT_SKU)
            ->setSubscriptionKey(static::TESTER_SUBSCRIPTION_KEY)
            ->setFkStore(static::TESTER_STORE)
            ->setFkLocale(static::TESTER_LOCALE);

        $this->availabilitySubscription->save();
    }

    /**
     * @return void
     */
    public function testSubscribeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->createSubscription();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeFailsWhenEmailIsInvalid()
    {
        $availabilityNotificationSubscription = $this->createInvalidSubscription();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeForAlreadySubscribedTypeShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->createSubscription();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForAlreadySubscribedShouldSucceed()
    {
        $availabilityNotificationSubscription = $this->createUnsubscribeTransfer();

        $availabilitySubscriptionExistenceRequest = $this->createAvailabilitySubscriptionExistenceRequestTransfer($availabilityNotificationSubscription);

        $result = $this->availabilityNotificationFacade->checkExistence($availabilitySubscriptionExistenceRequest);

        $this->assertNotNull($result->getAvailabilitySubscription());
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForNotSubscribedShouldFail()
    {
        $availabilityNotificationSubscription = $this->createSubscription();

        $availabilitySubscriptionExistenceRequest = $this->createAvailabilitySubscriptionExistenceRequestTransfer($availabilityNotificationSubscription);

        $result = $this->availabilityNotificationFacade->checkExistence($availabilitySubscriptionExistenceRequest);

        $this->assertNull($result->getAvailabilitySubscription());
    }

    /**
     * @return void
     */
    public function testUnsubscribeShouldSucceed()
    {
        $availabilityNotificationUnsubscribeTransfer = $this->createUnsubscribeTransfer();

        $response = $this->availabilityNotificationFacade->unsubscribe($availabilityNotificationUnsubscribeTransfer);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeWithIncorrectSubscriptionKeyShouldFail()
    {
        $availabilityNotificationUnsubscribeTransfer = $this->createUnsubscribeTransferWithIncorrectSubscriptionKey();

        $response = $this->availabilityNotificationFacade->unsubscribe($availabilityNotificationUnsubscribeTransfer);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testAnonymize()
    {
        $availabilityNotificationSubscription = $this->createSubscription();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $customerTransfer = $this->createCustomerTransfer();

        $this->availabilityNotificationFacade->anonymizeSubscription($customerTransfer);

        $availabilitySubscriptionExistenceRequest = $this->createAvailabilitySubscriptionExistenceRequestTransfer($availabilityNotificationSubscription);

        $result = $this->availabilityNotificationFacade->checkExistence($availabilitySubscriptionExistenceRequest);

        $this->assertNull($result->getAvailabilitySubscription());
    }

    /**
     * @return void
     */
    protected function setAvailabilityNotificationFacade()
    {
        $this->availabilityNotificationFacade = new AvailabilityNotificationFacade();
        $container = new Container();
        $availabilityNotificationDependencyProvider = new AvailabilityNotificationDependencyProvider();
        $availabilityNotificationDependencyProvider->provideBusinessLayerDependencies($container);

        $mailFacadeMock = $this->getMockBuilder(AvailabilityNotificationToMailFacadeInterface::class)->getMock();
        $container[AvailabilityNotificationDependencyProvider::FACADE_MAIL] = $mailFacadeMock;

        $availabilityNotificationBusinessFactory = new AvailabilityNotificationBusinessFactory();
        $availabilityNotificationBusinessFactory->setContainer($container);

        $this->availabilityNotificationFacade->setFactory($availabilityNotificationBusinessFactory);
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function createSubscription(): AvailabilitySubscriptionTransfer
    {
        $locale = new LocaleTransfer();
        $locale->setIdLocale(static::TESTER_LOCALE);

        $subscription = new AvailabilitySubscriptionTransfer();
        $subscription->setEmail(static::TESTER_EMAIL);
        $subscription->setSku(static::TESTER_PRODUCT_SKU);
        $subscription->setLocale($locale);
        $subscription->setSubscriptionKey(static::TESTER_SUBSCRIPTION_KEY);

        return $subscription;
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function createInvalidSubscription(): AvailabilitySubscriptionTransfer
    {
        $locale = new LocaleTransfer();
        $locale->setIdLocale(static::TESTER_LOCALE);

        $subscription = new AvailabilitySubscriptionTransfer();
        $subscription->setEmail(static::TESTER_EMAIL);
        $subscription->setSku(static::TESTER_PRODUCT_SKU);
        $subscription->setLocale($locale);
        $subscription->setSubscriptionKey(static::TESTER_EMAIL);

        return $subscription;
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function createUnsubscribeTransfer(): AvailabilitySubscriptionTransfer
    {
        $subscription = new AvailabilitySubscriptionTransfer();
        $subscription->setSubscriptionKey(static::TESTER_SUBSCRIPTION_KEY);
        $subscription->setEmail(static::TESTER_EMAIL);
        $subscription->setSku(static::TESTER_PRODUCT_SKU);

        return $subscription;
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function createUnsubscribeTransferWithIncorrectSubscriptionKey(): AvailabilitySubscriptionTransfer
    {
        $subscription = new AvailabilitySubscriptionTransfer();
        $subscription->setSubscriptionKey(static::INCORRECT_TESTER_SUBSCRIPTION_KEY);
        $subscription->setEmail(static::TESTER_EMAIL);
        $subscription->setSku(static::TESTER_PRODUCT_SKU);

        return $subscription;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(): CustomerTransfer
    {
        $customer = new CustomerTransfer();
        $customer->setCustomerReference(static::TESTER_CUSTOMER_REFERENCE);

        return $customer;
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscription
     *
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionExistenceRequestTransfer
     */
    protected function createAvailabilitySubscriptionExistenceRequestTransfer(AvailabilitySubscriptionTransfer $availabilitySubscription): AvailabilitySubscriptionExistenceRequestTransfer
    {
        $availabilitySubscriptionExistenceRequest = new AvailabilitySubscriptionExistenceRequestTransfer();
        $availabilitySubscriptionExistenceRequest->setSku($availabilitySubscription->getEmail());
        $availabilitySubscriptionExistenceRequest->setEmail($availabilitySubscription->getEmail());
        $availabilitySubscriptionExistenceRequest->setSubscriptionKey($availabilitySubscription->getEmail());

        return $availabilitySubscriptionExistenceRequest;
    }
}
