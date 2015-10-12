<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use SprykerFeature\Zed\Newsletter\Business\Exception\MissingNewsletterSubscriberException;

interface DoubleOptInHandlerInterface
{

    /**
     * @param SubscriberOptInSenderInterface $subscriberOptInSender
     */
    public function addSubscriberOptInSender(SubscriberOptInSenderInterface $subscriberOptInSender);

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     *
     * @throws MissingNewsletterSubscriberException
     */
    public function approveSubscriberByKey(NewsletterSubscriberInterface $newsletterSubscriber);

}
