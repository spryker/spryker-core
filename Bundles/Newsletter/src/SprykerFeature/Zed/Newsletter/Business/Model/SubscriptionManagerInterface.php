<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Model;

use Generated\Shared\Newsletter\NewsletterSubscriptionInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;

interface SubscriptionManagerInterface
{
    /**
     * @param NewsletterSubscriptionInterface $newsletterSubscription
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribe(NewsletterSubscriptionInterface $newsletterSubscription);
}
