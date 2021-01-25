<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractBundleConfig;

/**
 * @method \Spryker\Shared\ProductConfiguration\ProductConfigurationConfig getSharedConfig()
 */
class ProductConfigurationConfig extends AbstractBundleConfig
{
    /**
     * Number of seconds while response considered as valid.
     */
    protected const PRODUCT_CONFIGURATOR_RESPONSE_MAX_VALID_SECONDS = 60;

    /**
     * @api
     *
     * @return string
     */
    public function getProductConfiguratorEncryptionKey(): string
    {
        return $this->getSharedConfig()->getProductConfiguratorEncryptionKey();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getProductConfiguratorHexInitializationVector(): string
    {
        return $this->getSharedConfig()->getProductConfiguratorHexInitializationVector();
    }

    /**
     * @api
     *
     * @return int
     */
    public function getProductConfiguratorResponseMaxValidSeconds(): int
    {
        return static::PRODUCT_CONFIGURATOR_RESPONSE_MAX_VALID_SECONDS;
    }
}
