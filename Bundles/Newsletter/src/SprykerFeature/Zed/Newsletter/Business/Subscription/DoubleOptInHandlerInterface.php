<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionApprovalResultInterface;

interface DoubleOptInHandlerInterface
{

    /**
     * @param SubscriberOptInSenderInterface $subscriberOptInSender
     *
     * @return DoubleOptInHandlerInterface
     */
    public function addSubscriberOptInSender(SubscriberOptInSenderInterface $subscriberOptInSender);

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultInterface
     */
    public function approveSubscriberByKey(NewsletterSubscriberInterface $newsletterSubscriber);

}
