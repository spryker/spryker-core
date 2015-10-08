<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business;

use Generated\Shared\Newsletter\NewsletterSubscriptionInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method NewsletterDependencyContainer getDependencyContainer()
 */
class NewsletterFacade extends AbstractFacade
{
    /**
     * @param NewsletterSubscriptionInterface $newsletterSubscription
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribe(NewsletterSubscriptionInterface $newsletterSubscription)
    {
        $subscriptionResponse = $this->getDependencyContainer()
            ->createSubscriptionManager()
            ->subscribe($newsletterSubscription)
        ;

        return $subscriptionResponse;
    }
}
