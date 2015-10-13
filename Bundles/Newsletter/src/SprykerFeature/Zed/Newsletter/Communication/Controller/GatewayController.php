<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Communication\Controller;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionApprovalResultInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionRequestInterface;
use Generated\Shared\Newsletter\NewsletterSubscriptionResponseInterface;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Newsletter\Business\NewsletterFacade;

/**
 * @method NewsletterFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithSingleOptInAction(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->subscribeWithSingleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function subscribeWithDoubleOptInAction(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->subscribeWithDoubleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriberInterface $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultInterface
     */
    public function approveDoubleOptInSubscriberAction(NewsletterSubscriberInterface $newsletterSubscriber)
    {
        return $this->getFacade()->approveDoubleOptInSubscriber($newsletterSubscriber);
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function unsubscribeAction(NewsletterSubscriptionRequestInterface $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->unsubscribe($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestInterface $newsletterUnsubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseInterface
     */
    public function checkSubscriptionAction(NewsletterSubscriptionRequestInterface $newsletterUnsubscriptionRequest)
    {
        return $this->getFacade()->checkSubscription($newsletterUnsubscriptionRequest);
    }

}
