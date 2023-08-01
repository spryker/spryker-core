<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\ShipmentTypeCart\Business\Expander\ShipmentTypeExpander;
use Spryker\Zed\ShipmentTypeCart\Business\Expander\ShipmentTypeExpanderInterface;

/**
 * @method \Spryker\Zed\ShipmentTypeCart\ShipmentTypeCartConfig getConfig()
 */
class ShipmentTypeCartBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Expander\ShipmentTypeExpanderInterface
     */
    public function createShipmentTypeExpander(): ShipmentTypeExpanderInterface
    {
        return new ShipmentTypeExpander();
    }
}
