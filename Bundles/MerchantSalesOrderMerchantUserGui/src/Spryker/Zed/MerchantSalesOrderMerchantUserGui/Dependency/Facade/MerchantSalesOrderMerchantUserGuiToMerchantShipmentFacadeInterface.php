<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesOrderMerchantUserGui\Dependency\Facade;

use Generated\Shared\Transfer\ShipmentTransfer;

interface MerchantSalesOrderMerchantUserGuiToMerchantShipmentFacadeInterface
{
    /**
     * @param string $merchantReference
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return bool
     */
    public function isMerchantOrderShipment(
        string $merchantReference,
        ShipmentTransfer $shipmentTransfer
    ): bool;
}
