<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductQuantity\Reader;

use Spryker\Service\ProductQuantity\ProductQuantityConfig;

class ConfigReader implements ConfigReaderInterface
{
    /**
     * @var \Spryker\Service\ProductQuantity\ProductQuantityConfig
     */
    protected $config;

    /**
     * @param \Spryker\Service\ProductQuantity\ProductQuantityConfig $config
     */
    public function __construct(ProductQuantityConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return float
     */
    public function getDefaultMinimumQuantity(): float
    {
        return $this->config->getDefaultMinimumQuantity();
    }
}
