<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Builder;

use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfigurationSessionKeyBuilder implements ProductConfigurationSessionKeyBuilderInterface
{
    /**
     * @param string $sku
     *
     * @return string
     */
    public function getProductConfigurationSessionKey(string $sku): string
    {
        return sprintf('%s:%s', ProductConfigurationStorageConfig::SESSION_KEY, $sku);
    }
}
