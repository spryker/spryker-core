<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter\Service\Zed;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;

interface NewsletterStubInterface
{

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber);

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest);

}
