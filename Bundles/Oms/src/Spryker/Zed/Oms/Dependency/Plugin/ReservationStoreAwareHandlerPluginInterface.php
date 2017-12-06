<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Dependency\Plugin;

use Generated\Shared\Transfer\StoreTransfer;

interface ReservationStoreAwareHandlerPluginInterface
{
    /**
     * @api
     *
     * This plugin handles all necessary events related to stock updates, like Availability.
     *
     * @param string $sku
     *
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @return void
     */
    public function handleStock($sku, StoreTransfer $storeTransfer);
}
