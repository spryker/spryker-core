<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Newsletter;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method NewsletterFactory getFactory()
 */
class NewsletterClient extends AbstractClient implements NewsletterClientInterface
{

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFactory()->createZedNewsletterStub()
            ->subscribeWithSingleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFactory()->createZedNewsletterStub()
            ->subscribeWithDoubleOptIn($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        return $this->getFactory()->createZedNewsletterStub()
            ->approveDoubleOptInSubscriber($newsletterSubscriber);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFactory()->createZedNewsletterStub()
            ->unsubscribe($newsletterSubscriptionRequest);
    }

    /**
     * @param NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        return $this->getFactory()->createZedNewsletterStub()
            ->checkSubscription($newsletterUnsubscriptionRequest);
    }

}
