<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment;

use Spryker\Zed\Kernel\AbstractBundleConfig;

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
        return $this->getSharedConfig()::SHIPMENT_EXPENSE_TYPE;
    }
}
