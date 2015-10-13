<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter\Service;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionApprovalResultInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionRequestInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;

interface NewsletterClientInterface
{

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest);

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest);

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultInterface
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberInterface $newsletterSubscriber);

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function unsubscribe(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest);

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterUnsubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function checkSubscription(NewsletterSubscriptionRequestInterface $newsletterUnsubscriptionRequest);

}
