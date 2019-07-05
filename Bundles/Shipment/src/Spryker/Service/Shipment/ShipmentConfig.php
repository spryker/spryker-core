<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractBundleConfig;

class ShipmentConfig extends AbstractBundleConfig
{
    protected const SHIPMENT_EXPENSE_TYPE = 'SHIPMENT_EXPENSE_TYPE';

    /**
     * @return string
     */
    public function getShipmentExpenseType(): string
    {
        return static::SHIPMENT_EXPENSE_TYPE;
    }
}
