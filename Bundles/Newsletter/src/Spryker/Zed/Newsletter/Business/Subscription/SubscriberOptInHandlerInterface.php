<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;

interface SubscriberOptInHandlerInterface
{

    /**
     * @param NewsletterSubscriberTransfer $subscriberTransfer
     */
    public function optIn(NewsletterSubscriberTransfer $subscriberTransfer);

}
