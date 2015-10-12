<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter\Service;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionRequestInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use SprykerEngine\Client\Kernel\Service\AbstractClient;

/**
 * @method NewsletterDependencyContainer getDependencyContainer()
 */
class NewsletterClient extends AbstractClient implements NewsletterClientInterface
{

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->getDependencyContainer()->createZedNewsletterStub()
            ->subscribeWithSingleOptIn($newsletterSubscriptionRequest)
        ;
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->getDependencyContainer()->createZedNewsletterStub()
            ->subscribeWithDoubleOptIn($newsletterSubscriptionRequest)
        ;
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberInterface $newsletterSubscriber)
    {
        $this->getDependencyContainer()->createZedNewsletterStub()
            ->approveDoubleOptInSubscriber($newsletterSubscriber)
        ;
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function unsubscribe(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->getDependencyContainer()->createZedNewsletterStub()
            ->unsubscribe($newsletterSubscriptionRequest)
        ;
    }

}
