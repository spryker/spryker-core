<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilitySubscriptionTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;

interface UrlGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer
     *
     * @return string
     */
    public function createUnsubscriptionLink(AvailabilitySubscriptionTransfer $availabilitySubscriptionTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $localizedUrlTransfer
     *
     * @return string
     */
    public function generateProductUrl(LocalizedUrlTransfer $localizedUrlTransfer): string;
}
