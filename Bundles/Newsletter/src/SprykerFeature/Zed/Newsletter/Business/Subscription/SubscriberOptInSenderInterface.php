<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;

interface SubscriberOptInSenderInterface
{

    /**
     * @param NewsletterSubscriberTransfer $newsletterSubscriber
     *
     * @return bool
     */
    public function send(NewsletterSubscriberTransfer $newsletterSubscriber);

}
