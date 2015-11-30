<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method NewsletterDependencyContainer getDependencyContainer()
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
        $optInHandler = $this->getDependencyContainer()->createSingleOptInHandler();

        $subscriptionResponse = $this->getDependencyContainer()
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
        $optInHandler = $this->getDependencyContainer()->createDoubleOptInHandler();

        $subscriptionResponse = $this->getDependencyContainer()
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
        return $this->getDependencyContainer()
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
        $subscriptionResponse = $this->getDependencyContainer()
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
        $subscriptionResponse = $this->getDependencyContainer()
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
        $this->getDependencyContainer()
            ->createSubscriptionRequestHandler()
            ->assignCustomerToExistingSubscriber($newsletterSubscriber);
    }

}
