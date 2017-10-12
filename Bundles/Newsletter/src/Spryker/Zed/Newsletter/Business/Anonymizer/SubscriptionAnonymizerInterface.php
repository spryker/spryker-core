<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Newsletter\Business\Anonymizer;

use Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer;

interface SubscriptionAnonymizerInterface
{
    /**
     * @param \Generated\Shared\Transfer\NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer
     *
     * @return void
     */
    public function process(NewsletterSubscriptionRequestTransfer $newsletterSubscriptionRequestTransfer);
}
