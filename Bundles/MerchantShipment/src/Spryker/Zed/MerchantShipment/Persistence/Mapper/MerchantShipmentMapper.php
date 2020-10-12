<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Persistence\Mapper;

use Generated\Shared\Transfer\MerchantShipmentTransfer;
use Orm\Zed\Sales\Persistence\SpySalesShipment;

class MerchantShipmentMapper
{
    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesShipment $merchantShipment
     * @param \Generated\Shared\Transfer\MerchantShipmentTransfer $merchantShipmentTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantShipmentTransfer
     */
    public function mapMerchantShipmentEntityToMerchantShipmentTransfer(
        SpySalesShipment $merchantShipment,
        MerchantShipmentTransfer $merchantShipmentTransfer
    ): MerchantShipmentTransfer {
        return $merchantShipmentTransfer->fromArray($merchantShipment->toArray(), true);
    }
}
