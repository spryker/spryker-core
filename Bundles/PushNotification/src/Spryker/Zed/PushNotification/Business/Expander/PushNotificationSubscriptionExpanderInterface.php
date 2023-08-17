<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PushNotification\Business\Expander;

use ArrayObject;

interface PushNotificationSubscriptionExpanderInterface
{
    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer> $pushNotificationSubscriptionTransfers
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\PushNotificationSubscriptionTransfer>
     */
    public function expand(ArrayObject $pushNotificationSubscriptionTransfers): ArrayObject;
}
