<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Newsletter\Business\NewsletterBusinessFactory getFactory()
 */
class NewsletterFacade extends AbstractFacade implements NewsletterFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return bool
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        return $this->getFactory()
            ->createSubscriptionRequestHandler()
            ->assignCustomerToExistingSubscriber($newsletterSubscriber);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return void
     */
    public function install()
    {
        $this->getFactory()
            ->createNewsletterTypeInstaller()
            ->install();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return void
     */
    public function anonymizeSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        $this->getFactory()
            ->createSubscriptionAnonymizer()
            ->process($newsletterUnsubscriptionRequest);
    }
}
