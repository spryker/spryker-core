<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer;

interface ShipmentMethodPluginReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginSelectionTransfer
     */
    public function getShipmentMethodPlugins(): ShipmentMethodPluginSelectionTransfer;
}
