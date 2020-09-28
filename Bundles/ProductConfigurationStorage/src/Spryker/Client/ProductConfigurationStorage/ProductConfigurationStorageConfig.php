<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage;

use Spryker\Client\Kernel\AbstractBundleConfig;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConfig;

class ProductConfigurationStorageConfig extends AbstractBundleConfig
{
    /**
     * Product configuration session key
     */
    public const SESSION_KEY = 'PRODUCT_CONFIGURATION';

    /**
     * Number of seconds while response considered as valid
     */
    protected const PRODUCT_CONFIGURATION_RESPONSE_MAX_VALID_SECONDS = 60;

    /**
     * @return int
     */
    public function getProductConfigurationResponseMaxValidSeconds(): int
    {
        return static::PRODUCT_CONFIGURATION_RESPONSE_MAX_VALID_SECONDS;
    }

    /**
     * @return string
     */
    public function getProductConfigurationEncryptionKey(): string
    {
        return $this->get(ProductConfigurationConfig::ENCRYPTION_KEY);
    }
}
