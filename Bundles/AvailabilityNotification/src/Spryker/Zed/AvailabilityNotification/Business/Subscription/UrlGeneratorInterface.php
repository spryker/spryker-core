<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Subscription;

use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\LocalizedUrlTransfer;

interface UrlGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer
     *
     * @return string
     */
    public function createUnsubscriptionLink(AvailabilityNotificationSubscriptionTransfer $availabilityNotificationSubscriptionTransfer): string;

    /**
     * @param \Generated\Shared\Transfer\LocalizedUrlTransfer $localizedUrlTransfer
     *
     * @return string
     */
    public function generateProductUrl(LocalizedUrlTransfer $localizedUrlTransfer): string;
}
