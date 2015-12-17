<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method NewsletterBusinessFactory getFactory()
 */
class NewsletterFacade extends AbstractFacade
{

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        $optInHandler = $this->getFactory()->createSingleOptInHandler();

        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->processNewsletterSubscriptions($newsletterSubscriptionRequest, $optInHandler);

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        $optInHandler = $this->getFactory()->createDoubleOptInHandler();

        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->processNewsletterSubscriptions($newsletterSubscriptionRequest, $optInHandler);

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        return $this->getFactory()
            ->createDoubleOptInHandler()
            ->approveSubscriberByKey($newsletterSubscriber);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->checkNewsletterSubscriptions($newsletterUnsubscriptionRequest);

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->processNewsletterUnsubscriptions($newsletterUnsubscriptionRequest);

        return $subscriptionResponse;
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return void
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->assignCustomerToExistingSubscriber($newsletterSubscriber);
    }

}
