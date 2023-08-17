<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Extractor;

use ArrayObject;

class PushNotificationSubscriptionLocaleExtractor implements PushNotificationSubscriptionLocaleExtractorInterface
{
    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    public function extractLocaleTransfersIndexedByLocaleName(ArrayObject $pushNotificationSubscriptionTransfers): array
    {
        $localeTransfers = [];
        foreach ($pushNotificationSubscriptionTransfers as $notificationSubscriptionTransfer) {
            if ($notificationSubscriptionTransfer->getLocale()) {
                $localeName = $notificationSubscriptionTransfer->getLocaleOrFail()->getLocaleNameOrFail();
                $localeTransfers[$localeName] = $notificationSubscriptionTransfer->getLocaleOrFail();
            }
        }

        return $localeTransfers;
    }
}
