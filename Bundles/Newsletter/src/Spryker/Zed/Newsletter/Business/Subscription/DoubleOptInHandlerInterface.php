<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;

interface DoubleOptInHandlerInterface
{

    /**
     * @param SubscriberOptInSenderInterface $subscriberOptInSender
     *
     * @return DoubleOptInHandlerInterface
     */
    public function addSubscriberOptInSender(SubscriberOptInSenderInterface $subscriberOptInSender);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveSubscriberByKey(NewsletterSubscriberTransfer $newsletterSubscriber);

}
