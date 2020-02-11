<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Newsletter\Zed;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;
use Spryker\Client\ZedRequest\ZedRequestClient;

class NewsletterStub implements NewsletterStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClient
     */
    protected $zedStub;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClient $zedStub
     */
    public function __construct(ZedRequestClient $zedStub)
    {
        $this->zedStub = $zedStub;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithSingleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer $newsletterSubscriptionResponseTransfer */
        $newsletterSubscriptionResponseTransfer = $this->zedStub
            ->call('/newsletter/gateway/subscribe-with-single-opt-in', $newsletterSubscriptionRequest);

        return $newsletterSubscriptionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function subscribeWithDoubleOptIn(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer $newsletterSubscriptionResponseTransfer */
        $newsletterSubscriptionResponseTransfer = $this->zedStub
            ->call('/newsletter/gateway/subscribe-with-double-opt-in', $newsletterSubscriptionRequest);

        return $newsletterSubscriptionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer
     */
    public function approveDoubleOptInSubscriber(NewsletterSubscriberTransfer $newsletterSubscriber)
    {
        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionApprovalResultTransfer $newsletterSubscriptionApprovalResultTransfer */
        $newsletterSubscriptionApprovalResultTransfer = $this->zedStub
            ->call('/newsletter/gateway/approve-double-opt-in-subscriber', $newsletterSubscriber);

        return $newsletterSubscriptionApprovalResultTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function unsubscribe(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest)
    {
        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer $newsletterSubscriptionResponseTransfer */
        $newsletterSubscriptionResponseTransfer = $this->zedStub
            ->call('/newsletter/gateway/unsubscribe', $newsletterSubscriptionRequest);

        return $newsletterSubscriptionResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function checkSubscription(NewsletterSubscriptionRequestTransfer $newsletterUnsubscriptionRequest)
    {
        /** @var \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer $newsletterSubscriptionResponseTransfer */
        $newsletterSubscriptionResponseTransfer = $this->zedStub
            ->call('/newsletter/gateway/check-subscription', $newsletterUnsubscriptionRequest);

        return $newsletterSubscriptionResponseTransfer;
    }
}
