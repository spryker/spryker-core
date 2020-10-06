<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfiguratorTimestampResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_STORAGE_EXPIRED_TIMESTAMP = 'product_configuration_storage.validation.error.expired_timestamp';

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
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validate(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer->requireProductConfiguratorResponse();

        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponse();

        $timestampDiff = time() - $productConfiguratorResponseTransfer->getTimestamp();

        if ($timestampDiff > $this->config->getProductConfigurationResponseMaxValidSeconds()) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->addMessage(
                    (new MessageTransfer())
                        ->setMessage(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_STORAGE_EXPIRED_TIMESTAMP)
                )->setIsSuccessful(false);
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
