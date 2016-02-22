<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;
use Generated\Shared\Transfer\NewsletterTypeTransfer;

interface SubscriptionManagerInterface
{

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     */
    public function subscribe(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @return bool
     */
    public function isAlreadySubscribed(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriber
     * @param \Generated\Shared\Transfer\NewsletterTypeTransfer $newsletterType
     *
     * @return bool
     */
    public function unsubscribe(NewsletterSubscriberTransfer $newsletterSubscriber, NewsletterTypeTransfer $newsletterType);

}
