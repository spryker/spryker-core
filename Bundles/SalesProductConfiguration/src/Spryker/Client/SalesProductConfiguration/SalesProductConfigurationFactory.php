<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesProductConfiguration\Expander\ProductConfigurationItemExpander;
use Spryker\Client\SalesProductConfiguration\Expander\ProductConfigurationItemExpanderInterface;

/**
 * @method \Spryker\Client\SalesProductConfiguration\SalesProductConfigurationConfig getConfig()
 */
class SalesProductConfigurationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SalesProductConfiguration\Expander\ProductConfigurationItemExpanderInterface
     */
    public function createProductConfigurationItemExpander(): ProductConfigurationItemExpanderInterface
    {
        return new ProductConfigurationItemExpander();
    }
}
