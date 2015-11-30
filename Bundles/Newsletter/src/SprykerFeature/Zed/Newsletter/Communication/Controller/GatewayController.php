<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Communication\Controller;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use SprykerFeature\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use SprykerFeature\Zed\Newsletter\Business\NewsletterFacade;

/**
 * @method NewsletterFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptInAction(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->subscribeWithSingleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptInAction(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->subscribeWithDoubleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriberAction(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        return $this->getFacade()->approveDoubleOptInSubscriber($newsletterSubscriber);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribeAction(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->unsubscribe($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscriptionAction(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        return $this->getFacade()->checkSubscription($newsletterUnsubscriptionRequest);
    }

}
