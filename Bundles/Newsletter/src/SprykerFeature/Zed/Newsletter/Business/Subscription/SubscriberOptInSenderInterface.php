<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;

interface SubscriberOptInSenderInterface
{
    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     */
    public function send(NewsletterSubscriberInterface $newsletterSubscriber);
}
