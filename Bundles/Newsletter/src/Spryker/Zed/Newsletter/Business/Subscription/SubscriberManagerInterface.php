<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Subscription;

use Generated\Shared\Transfer\NewsletterSubscriberTransfer;

interface SubscriberManagerInterface
{
    /**
     * @param string $email
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer|null
     */
    public function loadSubscriberByEmail($email);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return \Generated\Shared\Transfer\NewsletterSubscriberTransfer
     */
    public function createSubscriberFromTransfer(NewsletterSubscriberTransfer $newsletterSubscriberTransfer);

    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriberTransfer $newsletterSubscriberTransfer
     *
     * @return bool
     */
    public function assignCustomerToExistingSubscriber(NewsletterSubscriberTransfer $newsletterSubscriberTransfer);
}
