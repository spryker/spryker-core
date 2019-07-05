<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Shipment\ConfigReader;

use Spryker\Service\Shipment\ShipmentConfig;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var \Spryker\Service\Shipment\ShipmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\Shipment\ShipmentConfig $config
     */
    public function __construct(ShipmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getShipmentExpenseType(): string
    {
        return $this->config->getShipmentExpenseType();
    }
}
