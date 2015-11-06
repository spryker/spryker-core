<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Newsletter\NewsletterSubscriberInterface;

interface SubscriberOptInHandlerInterface
{

    /**
     * @param NewsletterSubscriberInterface $subscriberTransfer
     */
    public function optIn(NewsletterSubscriberInterface $subscriberTransfer);

}
