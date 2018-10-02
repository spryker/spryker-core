<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Newsletter\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterSubscriberQuery;
use Orm\Zed\Newsletter\Persistence\SpyNewsletterType;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Newsletter\Business\NewsletterBusinessFactory;
use Spryker\Zed\Newsletter\Business\NewsletterFacade;
use Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailInterface;
use Spryker\Zed\Newsletter\NewsletterDependencyProvider;
use Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Newsletter
 * @group Business
 * @group Facade
 * @group NewsletterFacadeTest
 * Add your own group annotations below this line
 */
class NewsletterFacadeTest extends Unit
{
    public const TEST_TYPE1 = 'TEST_TYPE1';
    public const TEST_TYPE2 = 'TEST_TYPE2';

    /**
     * @var \Spryker\Zed\Newsletter\Business\NewsletterFacade
     */
    protected $newsletterFacade;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->setNewsletterFacade();
        $this->setTestNewsletterTypes();
    }

    /**
     * @return void
     */
    public function testSubscribeWithSingleOptInShouldSucceed()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);
        $this->addTestType2ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->subscribeWithSingleOptIn($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    /**
     * @return void
     */
    public function testSubscribeWithSingleOptInFailsWhenEmailIsInvalid()
    {
        // Assign
        $subscriptionRequestTransfer = (new NewsletterSubscriptionRequestTransfer())
            ->setNewsletterSubscriber($this->createInvalidSubscriber());

        $this->addTestType1ToSubscriptionRequest($subscriptionRequestTransfer);
        $this->addTestType2ToSubscriptionRequest($subscriptionRequestTransfer);

        // Act
        $actualResult = $this->newsletterFacade->subscribeWithSingleOptIn($subscriptionRequestTransfer);

        // Assert
        foreach ($actualResult->getSubscriptionResults() as $result) {
            $this->assertFalse($result->getIsSuccess());
        }
    }

    /**
     * @return void
     */
    public function testSubscribeForAlreadySubscribedTypeShouldFail()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $response = $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $this->assertFalse($response->getSubscriptionResults()[0]->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeWithDoubleOptInShouldSucceed()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);
        $this->addTestType2ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->subscribeWithDoubleOptIn($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    /**
     * @return void
     */
    public function testSubscribeWithDoubleOptInFailsWhenEmailIsInvalid()
    {
        // Assign
        $subscriptionRequestTransfer = (new NewsletterSubscriptionRequestTransfer())
            ->setNewsletterSubscriber($this->createInvalidSubscriber());

        $this->addTestType1ToSubscriptionRequest($subscriptionRequestTransfer);
        $this->addTestType2ToSubscriptionRequest($subscriptionRequestTransfer);

        // Act
        $actualResult = $this->newsletterFacade->subscribeWithDoubleOptIn($subscriptionRequestTransfer);

        // Assert
        foreach ($actualResult->getSubscriptionResults() as $result) {
            $this->assertFalse($result->getIsSuccess());
        }
    }

    /**
     * @return void
     */
    public function testApproveDoubleOptInSubscriberShouldSucceed()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithDoubleOptIn($request);

        $response = $this->newsletterFacade->approveDoubleOptInSubscriber($subscriber);

        $this->assertTrue($response->getIsSuccess(), (string)$response->getErrorMessage());
    }

    /**
     * @return void
     */
    public function testApproveNonExistentDoubleOptInSubscriberShouldFail()
    {
        $subscriber = $this->createSubscriber();

        $response = $this->newsletterFacade->approveDoubleOptInSubscriber($subscriber);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeFromTypesShouldSucceed()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);
        $this->addTestType2ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $response = $this->newsletterFacade->unsubscribe($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    /**
     * @return void
     */
    public function testUnsubscribeFromNotSubscribedTypesShouldFail()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();

        $request->setNewsletterSubscriber($subscriber);
        $this->addTestType2ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->unsubscribe($request);

        foreach ($response->getSubscriptionResults() as $result) {
            $this->assertFalse($result->getIsSuccess(), (string)$result->getErrorMessage());
        }
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForSubscribedTypesShouldSucceed()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $this->newsletterFacade->subscribeWithSingleOptIn($request);

        $response = $this->newsletterFacade->checkSubscription($request);

        $result = $response->getSubscriptionResults()[0];
        $this->assertTrue($result->getIsSuccess(), (string)$result->getErrorMessage());
    }

    /**
     * @return void
     */
    public function testCheckSubscriptionForNotSubscribedTypesShouldFail()
    {
        $request = new NewsletterSubscriptionRequestTransfer();
        $subscriber = $this->createSubscriber();
        $request->setNewsletterSubscriber($subscriber);

        $this->addTestType1ToSubscriptionRequest($request);

        $response = $this->newsletterFacade->checkSubscription($request);

        $result = $response->getSubscriptionResults()[0];
        $this->assertFalse($result->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testAssignCustomerToExistingSubscriber()
    {
        $newsletterSubscriberTransfer = $this->createSubscriber();

        $subscriberQuery = SpyNewsletterSubscriberQuery::create();
        $subscriberQuery->filterByEmail($newsletterSubscriberTransfer->getEmail());
        $subscriberQuery->filterBySubscriberKey($newsletterSubscriberTransfer->getSubscriberKey());
        $subscriberEntity = $subscriberQuery->findOneOrCreate();
        if ($subscriberEntity->getFkCustomer()) {
            $subscriberEntity->setFkCustomer(null);
        }
        $subscriberEntity->save();

        $customerQuery = SpyCustomerQuery::create();
        $customerQuery->filterByEmail($newsletterSubscriberTransfer->getEmail());
        $customerQuery->filterByCustomerReference('123');
        $customer = $customerQuery->findOneOrCreate();
        $customer->save();

        $newsletterSubscriberTransfer->setFkCustomer($customer->getIdCustomer());

        $result = $this->newsletterFacade->assignCustomerToExistingSubscriber($newsletterSubscriberTransfer);
        $this->assertTrue($result);

        $queryContainer = new NewsletterQueryContainer();
        $subscriberEntity = $queryContainer->querySubscriber()->filterByEmail($newsletterSubscriberTransfer->getEmail())->findOne();

        $this->assertSame($customer->getIdCustomer(), $subscriberEntity->getFkCustomer());
    }

    /**
     * @return void
     */
    protected function setNewsletterFacade()
    {
        $this->newsletterFacade = new NewsletterFacade();
        $container = new Container();
        $newsletterDependencyProvider = new NewsletterDependencyProvider();
        $newsletterDependencyProvider->provideBusinessLayerDependencies($container);

        $mailFacadeMock = $this->getMockBuilder(NewsletterToMailInterface::class)->getMock();
        $container[NewsletterDependencyProvider::FACADE_MAIL] = $mailFacadeMock;

        $newsletterBusinessFactory = new NewsletterBusinessFactory();
        $newsletterBusinessFactory->setContainer($container);

        $this->newsletterFacade->setFactory($newsletterBusinessFactory);
    }

    /**
     * @return void
     */
    protected function setTestNewsletterTypes()
    {
        $typeEntity = new SpyNewsletterType();
        $typeEntity->setName(self::TEST_TYPE1);
        $typeEntity->save();

        $typeEntity = new SpyNewsletterType();
        $typeEntity->setName(self::TEST_TYPE2);
        $typeEntity->save();
    }

    /**
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer
     */
    protected function createSubscriber()
    {
        $subscriber = new NewsletterSubscriberTransfer();
        $subscriber->setEmail('example@spryker.com');
        $subscriber->setSubscriberKey('example@spryker.com');

        return $subscriber;
    }

    /**
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer
     */
    protected function createInvalidSubscriber()
    {
        $subscriber = new NewsletterSubscriberTransfer();
        $subscriber->setEmail('invalid<>example@spryker.com');
        $subscriber->setSubscriberKey('invalid<>example@spryker.com');

        return $subscriber;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $request
     *
     * @return void
     */
    protected function addTestType1ToSubscriptionRequest(NewsletterSubscriptionRequestTransfer $request)
    {
        $type1 = new NewsletterTypeTransfer();
        $type1->setName(self::TEST_TYPE1);

        $request->addSubscriptionType($type1);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $request
     *
     * @return void
     */
    protected function addTestType2ToSubscriptionRequest(NewsletterSubscriptionRequestTransfer $request)
    {
        $type2 = new NewsletterTypeTransfer();
        $type2->setName(self::TEST_TYPE2);

        $request->addSubscriptionType($type2);
    }
}
