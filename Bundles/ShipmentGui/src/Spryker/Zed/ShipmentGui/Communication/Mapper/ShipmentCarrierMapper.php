<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Mapper;

use Generated\Shared\Transfer\ShipmentCarrierTransfer;

class ShipmentCarrierMapper
{
    /**
     * @param array $requestData
     * @param \Generated\Shared\Transfer\ShipmentCarrierTransfer $shipmentCarrierTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentCarrierTransfer
     */
    public function mapRequestDataToShipmentCarrierTransfer(array $requestData, ShipmentCarrierTransfer $shipmentCarrierTransfer): ShipmentCarrierTransfer
    {
        return $shipmentCarrierTransfer->fromArray($requestData, true);
    }
}
