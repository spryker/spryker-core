<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterTypeInterface;

interface SubscriptionManagerInterface
{

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     */
    public function subscribe(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType);

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     *
     * @return bool
     */
    public function isAlreadySubscribed(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType);

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     * @param NewsletterTypeInterface $newsletterType
     *
     * @return bool
     */
    public function unsubscribe(NewsletterSubscriberInterface $newsletterSubscriber, NewsletterTypeInterface $newsletterType);

}
