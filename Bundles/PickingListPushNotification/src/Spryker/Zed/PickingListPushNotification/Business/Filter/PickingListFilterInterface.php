<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingListPushNotification\Business\Filter;

use ArrayObject;

interface PickingListFilterInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer> $pickingListTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\PickingListTransfer>
     */
    public function filterNotifiablePickingLists(ArrayObject $pickingListTransfers): ArrayObject;
}
