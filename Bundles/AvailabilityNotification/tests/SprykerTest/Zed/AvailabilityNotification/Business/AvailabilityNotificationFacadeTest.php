<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
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
    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected $availabilityNotificationFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setAvailabilityNotificationFacade();
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
        $availabilityNotificationSubscription = $this->createSubscription();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $response = $this->availabilityNotificationFacade->checkSubscription($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForNotSubscribedShouldFail()
    {
        $availabilityNotificationSubscription = $this->createSubscription();

        $response = $this->availabilityNotificationFacade->checkSubscription($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
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
    protected function createSubscription()
    {
        $subscription = new AvailabilitySubscriptionTransfer();
        $subscription->setEmail('example@spryker.com');
        $subscription->setSku('123_123');
        $subscription->setSubscriptionKey('example@spryker.com');

        return $subscription;
    }

    /**
     * @return \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer
     */
    protected function createInvalidSubscription()
    {
        $subscription = new AvailabilitySubscriptionTransfer();
        $subscription->setEmail('invalid<>example@spryker.com');
        $subscription->setSku('123_123');
        $subscription->setSubscriptionKey('invalid<>example@spryker.com');

        return $subscription;
    }
}
