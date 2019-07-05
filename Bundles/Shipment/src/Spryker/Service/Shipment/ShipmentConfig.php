<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment;

use Spryker\Service\Kernel\AbstractBundleConfig;
use Spryker\Shared\Shipment\ShipmentConstants;

class ShipmentConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getShipmentExpenseType(): string
    {
        return $this->get(
            ShipmentConstants::SHIPMENT_EXPENSE_TYPE,
            ShipmentConstants::SHIPMENT_EXPENSE_TYPE
        );
    }
}
