<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;

interface DoubleOptInHandlerInterface
{

    /**
     * @param SubscriberOptInSenderInterface $subscriberOptInSender
     *
     * @return DoubleOptInHandlerInterface
     */
    public function addSubscriberOptInSender(SubscriberOptInSenderInterface $subscriberOptInSender);

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveSubscriberByKey(NewsletterSubscriberTransfer $newsletterSubscriber);

}
