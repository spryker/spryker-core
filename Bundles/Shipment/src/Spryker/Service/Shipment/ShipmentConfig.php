<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\Shipment\ShipmentConfig getSharedConfig()
 */
class ShipmentConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getShipmentExpenseType(): string
    {
        return $this->getSharedConfig()->getShipmentExpenseType();
    }
}
