<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTransfer;

interface ShipmentInterface
{
    /**
     * @param int $idShipment
     *
     * @return \Generated\Shared\Transfer\ShipmentTransfer
     */
    public function getShipmentTransferById(int $idShipment): ShipmentTransfer;
}
