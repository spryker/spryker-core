<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Communication\Controller;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;
use Spryker\Zed\Newsletter\Business\NewsletterFacade;

/**
 * @method NewsletterFacade getFacade()
 */
class GatewayController extends AbstractGatewayController
{

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptInAction(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->subscribeWithSingleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptInAction(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->subscribeWithDoubleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriberAction(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        return $this->getFacade()->approveDoubleOptInSubscriber($newsletterSubscriber);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribeAction(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFacade()->unsubscribe($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscriptionAction(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        return $this->getFacade()->checkSubscription($newsletterUnsubscriptionRequest);
    }

}
