<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Shipment\Helper;

use Codeception\Module;

trait ShipmentMethodDataHelperTrait
{
    /**
     * @return \SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper
     */
    protected function getShipmentMethodDataHelper(): ShipmentMethodDataHelper
    {
        /** @var \SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper $shipmentMethodDataHelper */
        $shipmentMethodDataHelper = $this->getModule('\\' . ShipmentMethodDataHelper::class);

        return $shipmentMethodDataHelper;
    }

    /**
     * @param string $name
     *
     * @return \Codeception\Module
     */
    abstract protected function getModule(string $name): Module;
}
