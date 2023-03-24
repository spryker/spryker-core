<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList;

use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\PickingList\PickingListConfig getSharedConfig()
 */
class PickingListConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Returns the list of statuses when order picking list is started.
     *
     * @api
     *
     * @return list<string>
     */
    public function getOrderPickingListStartedStatuses(): array
    {
        return $this->getSharedConfig()->getOrderPickingListStartedStatuses();
    }
}
