<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Dependency\Facade;

interface MerchantProfileStorageToEventBehaviorFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     *
     * @return int[]
     */
    public function getEventTransferIds(array $eventTransfers);

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $foreignKeyColumnName
     *
     * @return int[]
     */
    public function getEventTransferForeignKeys(array $eventTransfers, $foreignKeyColumnName);
}
