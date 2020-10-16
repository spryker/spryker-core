<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Client\ProductConfiguration\ProductConfigurationConfig;

class ProductConfiguratorTimestampResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_STORAGE_EXPIRED_TIMESTAMP = 'product_configuration.validation.error.expired_timestamp';

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationConfig
     */
    protected $config;

    /**
     * @param \Spryker\Client\ProductConfiguration\ProductConfigurationConfig $config
     */
    public function __construct(ProductConfigurationConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validateProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer->requireProductConfiguratorResponse();

        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponse();

        $timestampDiff = time() - $productConfiguratorResponseTransfer->getTimestamp();

        if ($timestampDiff > $this->config->getProductConfigurationResponseMaxValidSeconds()) {
            return $this->getErrorResponse(
                $productConfiguratorResponseProcessorResponseTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_STORAGE_EXPIRED_TIMESTAMP
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function getErrorResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        string $errorMessage
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $productConfiguratorResponseProcessorResponseTransfer
            ->addMessage((new MessageTransfer())->setValue($errorMessage))
            ->setIsSuccessful(false);
    }
}
