<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Newsletter\Service\Zed;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionRequestInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use SprykerFeature\Client\ZedRequest\Service\ZedRequestClient;

class NewsletterStub implements NewsletterStubInterface
{

    /**
     * @var ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->zedStub->call('/newsletter/gateway/subscribe-with-single-opt-in', $newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->zedStub->call('/newsletter/gateway/subscribe-with-double-opt-in', $newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberInterface $newsletterSubscriber)
    {
        $this->zedStub->call('/newsletter/gateway/approve-double-opt-in-subscriber', $newsletterSubscriber);
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function unsubscribe(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        $this->zedStub->call('/newsletter/gateway/unsubscribe', $newsletterSubscriptionRequest);
    }
}
