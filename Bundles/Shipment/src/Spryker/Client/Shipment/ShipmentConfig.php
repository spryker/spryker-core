<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Shipment;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Shipment\ShipmentConfig getSharedConfig()
 */
class ShipmentConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isMultiShipmentSelectionEnabled(): bool
    {
        return $this->getSharedConfig()->isMultiShipmentSelectionEnabled();
    }
}
