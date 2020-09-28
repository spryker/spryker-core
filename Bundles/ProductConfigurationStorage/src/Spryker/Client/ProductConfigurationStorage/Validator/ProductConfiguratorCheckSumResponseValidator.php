<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Validator;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfiguratorCheckSumResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_GROUP_KEY_IS_NOT_PROVIDED = 'product_configuration.validation.error.group_key_is_not_provided';

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig $config
     */
    public function __construct(ProductConfigurationStorageConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validate(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $key = $this->config->getProductConfigurationEncryptionKey();

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
