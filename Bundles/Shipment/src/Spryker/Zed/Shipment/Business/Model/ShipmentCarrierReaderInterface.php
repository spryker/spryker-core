<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\Model;

interface ShipmentCarrierReaderInterface
{
    /**
     * @return array<\Generated\Shared\Transfer\ShipmentCarrierTransfer>
     */
    public function getCarriers();
}
