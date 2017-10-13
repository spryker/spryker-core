<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;

interface SubscriptionRequestHandlerInterface
{
    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     * @param \Spryker\Zed\Newsletter\Business\Subscription\SubscriberOptInHandlerInterface $optInHandler
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function processNewsletterSubscriptions(
        NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest,
        SubscriberOptInHandlerInterface $optInHandler
    );

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriptionResponseTransfer
     */
    public function processNewsletterUnsubscriptions(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequest);
}
