<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Newsletter\Business\Anonymizer\SubscriptionAnonymizer;
use Spryker\Zed\Newsletter\Business\Internal\NewsletterTypeInstaller;
use Spryker\Zed\Newsletter\Business\Subscription\DoubleOptInHandler;
use Spryker\Zed\Newsletter\Business\Subscription\SingleOptInHandler;
use Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGenerator;
use Spryker\Zed\Newsletter\Business\Subscription\SubscriberManager;
use Spryker\Zed\Newsletter\Business\Subscription\SubscriptionManager;
use Spryker\Zed\Newsletter\Business\Subscription\SubscriptionRequestHandler;
use Spryker\Zed\Newsletter\NewsletterDependencyProvider;

/**
 * @method \Spryker\Zed\Newsletter\NewsletterConfig getConfig()
 * @method \Spryker\Zed\Newsletter\Persistence\NewsletterQueryContainer getQueryContainer()
 */
class NewsletterBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Newsletter\Business\Subscription\SubscriptionRequestHandler
     */
    public function createSubscriptionRequestHandler()
    {
        return new SubscriptionRequestHandler(
            $this->createSubscriptionManager(),
            $this->createSubscriberManager(),
            $this->getQueryContainer(),
            $this->getMailFacade()
        );
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Subscription\SubscriptionManagerInterface
     */
    protected function createSubscriptionManager()
    {
        return new SubscriptionManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Subscription\SubscriberManagerInterface
     */
    protected function createSubscriberManager()
    {
        return new SubscriberManager(
            $this->getQueryContainer(),
            $this->createSubscriberKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Subscription\SubscriberOptInHandlerInterface
     */
    public function createSingleOptInHandler()
    {
        return new SingleOptInHandler(
            $this->getQueryContainer(),
            $this->createSubscriberKeyGenerator()
        );
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Subscription\SubscriberOptInHandlerInterface|\Spryker\Zed\Newsletter\Business\Subscription\DoubleOptInHandlerInterface
     */
    public function createDoubleOptInHandler()
    {
        $subscriberOptInHandler = new DoubleOptInHandler(
            $this->getQueryContainer(),
            $this->createSubscriberKeyGenerator()
        );

        return $subscriberOptInHandler;
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Anonymizer\SubscriptionAnonymizerInterface
     */
    public function createSubscriptionAnonymizer()
    {
        return new SubscriptionAnonymizer(
            $this->getQueryContainer(),
            $this->createSubscriptionRequestHandler()
        );
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface
     */
    protected function createSubscriberKeyGenerator()
    {
        return new SubscriberKeyGenerator();
    }

    /**
     * @return \Spryker\Zed\Newsletter\Business\Internal\NewsletterTypeInstaller
     */
    public function createNewsletterTypeInstaller()
    {
        return new NewsletterTypeInstaller(
            $this->getQueryContainer(),
            $this->getConfig()->getNewsletterTypes()
        );
    }

    /**
     * @return \Spryker\Zed\Newsletter\Dependency\Facade\NewsletterToMailInterface
     */
    protected function getMailFacade()
    {
        return $this->getProvidedDependency(NewsletterDependencyProvider::FACADE_MAIL);
    }

}
