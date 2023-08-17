<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Expander;

use ArrayObject;
use Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface;

class PushNotificationSubscriptionPushNotificationProviderExpander implements PushNotificationSubscriptionExpanderInterface
{
    /**
     * @var \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface
     */
    protected PushNotificationProviderReaderInterface $pushNotificationProviderReader;

    /**
     * @param \Spryker\Zed\PushNotification\Business\Reader\PushNotificationProviderReaderInterface $pushNotificationProviderReader
     */
    public function __construct(
        PushNotificationProviderReaderInterface $pushNotificationProviderReader
    ) {
        $this->pushNotificationProviderReader = $pushNotificationProviderReader;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function expand(ArrayObject $pushNotificationSubscriptionTransfers): ArrayObject
    {
        $pushNotificationProviderTransfersIndexedByName = $this
            ->pushNotificationProviderReader
            ->getPushNotificationProviderTransfersIndexedByName();

        foreach ($pushNotificationSubscriptionTransfers as $pushNotificationSubscriptionTransfer) {
            $pushNotificationProviderName = $pushNotificationSubscriptionTransfer->getProviderOrFail()->getNameOrFail();
            $pushNotificationProviderTransfer = $pushNotificationProviderTransfersIndexedByName[$pushNotificationProviderName];
            $pushNotificationSubscriptionTransfer->setProvider($pushNotificationProviderTransfer);
        }

        return $pushNotificationSubscriptionTransfers;
    }
}
