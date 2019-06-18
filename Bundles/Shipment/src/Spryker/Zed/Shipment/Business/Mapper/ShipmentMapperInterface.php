<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Mapper;

use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentMapperInterface
{
    /**
     * @param array $formData
     * @param \Generated\Shared\Transfer\ShipmentTransfer $shipmentTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function mapFormDataToShipmentTransfer(array $formData, ShipmentTransfer $shipmentTransfer): ShipmentTransfer;
}
