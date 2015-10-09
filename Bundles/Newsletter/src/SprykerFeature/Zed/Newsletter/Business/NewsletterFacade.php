<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business;

use Generated\Shared\Newsletter\NewsletterSubscriptionRequestInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method NewsletterDependencyContainer getDependencyContainer()
 */
class NewsletterFacade extends AbstractFacade
{
    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        $optInHandler = $this->getDependencyContainer()->createSingleOptInHandler();

        $subscriptionResponse = $this->getDependencyContainer()
            ->createSubscriptionRequestHandler($optInHandler)
            ->processNewsletterSubscriptions($newsletterSubscriptionRequest)
        ;

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        $optInHandler = $this->getDependencyContainer()->createDoubleOptInHandler();

        $subscriptionResponse = $this->getDependencyContainer()
            ->createSubscriptionRequestHandler($optInHandler)
            ->processNewsletterSubscriptions($newsletterSubscriptionRequest)
        ;

        return $subscriptionResponse;
    }

}
