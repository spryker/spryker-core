<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Newsletter\Business\NewsletterBusinessFactory getFactory()
 */
class NewsletterFacade extends AbstractFacade
{

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
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
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        return $this->getFactory()
            ->createDoubleOptInHandler()
            ->approveSubscriberByKey($newsletterSubscriber);
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->checkNewsletterSubscriptions($newsletterUnsubscriptionRequest);

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        $subscriptionResponse = $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->processNewsletterUnsubscriptions($newsletterUnsubscriptionRequest);

        return $subscriptionResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
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
