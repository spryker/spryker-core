<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotificationWebPushPhp\Business\Filter;

use ArrayObject;

class PushNotificationFilter implements PushNotificationFilterInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer> $pushNotificationTransfers
     * @param string $providerName
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PushNotificationTransfer>
     */
    public function filterPushNotificationCollectionByProviderName(
        ArrayObject $pushNotificationTransfers,
        string $providerName
    ): ArrayObject {
        $filteredPushNotificationTransfers = new ArrayObject();
        foreach ($pushNotificationTransfers as $i => $pushNotificationTransfer) {
            if ($pushNotificationTransfer->getProviderOrFail()->getNameOrFail() !== $providerName) {
                continue;
            }
            $filteredPushNotificationTransfers->offsetSet($i, $pushNotificationTransfer);
        }

        return $filteredPushNotificationTransfers;
    }
}
