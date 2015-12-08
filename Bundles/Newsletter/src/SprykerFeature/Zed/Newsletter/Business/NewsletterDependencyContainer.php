<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business;

use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriberKeyGenerator;
use SprykerFeature\Zed\Newsletter\Business\Subscription\DoubleOptInHandler;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SingleOptInHandler;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriberManager;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriptionManager;
use Generated\Zed\Ide\FactoryAutoCompletion\NewsletterBusiness;
use SprykerFeature\Zed\Newsletter\Business\Subscription\DoubleOptInHandlerInterface;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriberKeyGeneratorInterface;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriberManagerInterface;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriberOptInHandlerInterface;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriptionManagerInterface;
use SprykerFeature\Zed\Newsletter\Business\Subscription\SubscriptionRequestHandler;
use SprykerFeature\Zed\Newsletter\NewsletterConfig;
use SprykerFeature\Zed\Newsletter\NewsletterDependencyProvider;
use SprykerFeature\Zed\Newsletter\Persistence\NewsletterQueryContainer;
use SprykerEngine\Zed\Kernel\Business\AbstractBusinessDependencyContainer;

/**
 * @method NewsletterConfig getConfig()
 * @method NewsletterQueryContainer getQueryContainer()
 */
class NewsletterDependencyContainer extends AbstractBusinessDependencyContainer
{

    /**
     * @return SubscriptionRequestHandler
     */
    public function createSubscriptionRequestHandler()
    {
        return new SubscriptionRequestHandler(
            $this->createSubscriptionManager(),
            $this->createSubscriberManager(),
            $this->getQueryContainer()
        );
    }

    /**
     * @return SubscriptionManagerInterface
     */
    protected function createSubscriptionManager()
    {
        return new SubscriptionManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return SubscriberManagerInterface
     */
    protected function createSubscriberManager()
    {
        return new SubscriberManager(
            $this->getQueryContainer(),
            $this->createSubscriberKeyGenerator()
        );
    }

    /**
     * @return SubscriberOptInHandlerInterface
     */
    public function createSingleOptInHandler()
    {
        return new SingleOptInHandler(
            $this->getQueryContainer(),
            $this->createSubscriberKeyGenerator()
        );
    }

    /**
     * @return SubscriberOptInHandlerInterface|DoubleOptInHandlerInterface
     */
    public function createDoubleOptInHandler()
    {
        $subscriberOptInHandler = new DoubleOptInHandler(
            $this->getQueryContainer(),
            $this->createSubscriberKeyGenerator()
        );

        $optInSenderPlugins = $this->getProvidedDependency(NewsletterDependencyProvider::DOUBLE_OPT_IN_SENDER_PLUGINS);

        foreach ($optInSenderPlugins as $optInSenderPlugin) {
            $subscriberOptInHandler->addSubscriberOptInSender($optInSenderPlugin);
        }

        return $subscriberOptInHandler;
    }

    /**
     * @return SubscriberKeyGeneratorInterface
     */
    protected function createSubscriberKeyGenerator()
    {
        return new SubscriberKeyGenerator();
    }

}
