<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Newsletter;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Newsletter\NewsletterFactory getFactory()
 */
class NewsletterClient extends AbstractClient implements NewsletterClientInterface
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
        return $this->getFactory()->createZedNewsletterStub()
            ->subscribeWithSingleOptIn($newsletterSubscriptionRequest);
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
        return $this->getFactory()->createZedNewsletterStub()
            ->subscribeWithDoubleOptIn($newsletterSubscriptionRequest);
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
        return $this->getFactory()->createZedNewsletterStub()
            ->approveDoubleOptInSubscriber($newsletterSubscriber);
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
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        return $this->getFactory()->createZedNewsletterStub()
            ->unsubscribe($newsletterSubscriptionRequest);
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
        return $this->getFactory()->createZedNewsletterStub()
            ->checkSubscription($newsletterUnsubscriptionRequest);
    }
}
