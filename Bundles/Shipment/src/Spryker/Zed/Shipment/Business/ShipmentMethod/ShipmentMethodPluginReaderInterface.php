<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Business\ShipmentMethod;

use Generated\Shared\Transfer\ShipmentMethodPluginCollectionTransfer;

interface ShipmentMethodPluginReaderInterface
{
    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodPluginCollectionTransfer
     */
    public function getShipmentMethodPlugins(): ShipmentMethodPluginCollectionTransfer;
}
