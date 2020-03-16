<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantStorage\Business;

interface MerchantStorageFacadeInterface
{
    /**
     * Specification:
     * - Publishes merchant data to storage based on merchant events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[]|\Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     *
     * @return void
     */
    public function writeByMerchantEvents(array $eventTransfers): void;
}
