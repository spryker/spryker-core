<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesProductConfiguration;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SalesProductConfiguration\Expander\ItemExpander;
use Spryker\Client\SalesProductConfiguration\Expander\ItemExpanderInterface;

class SalesProductConfigurationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SalesProductConfiguration\Expander\ItemExpanderInterface
     */
    public function createItemExpander(): ItemExpanderInterface
    {
        return new ItemExpander();
    }
}
