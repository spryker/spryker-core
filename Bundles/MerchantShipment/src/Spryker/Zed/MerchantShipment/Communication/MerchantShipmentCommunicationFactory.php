<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantShipment\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantShipment\Communication\Expander\ShipmentExpander;
use Spryker\Zed\MerchantShipment\Communication\Expander\ShipmentExpanderInterface;

class MerchantShipmentCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantShipment\Communication\Expander\ShipmentExpanderInterface
     */
    public function createShipmentExpander(): ShipmentExpanderInterface
    {
        return new ShipmentExpander();
    }
}
