<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface;
use Spryker\Client\ProductConfiguration\ProductConfigurationConfig;

class ProductConfiguratorCheckSumResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_VALID_RESPONSE_CHECKSUM = 'product_configuration.validation.error.not_valid_response_checksum';

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface
     */
    protected $productConfigurationDataChecksumGenerator;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationConfig
     */
    protected $productConfigurationConfig;

    /**
     * @param \Spryker\Client\ProductConfiguration\ProductConfigurationConfig $productConfigurationConfig
     * @param \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface $productConfigurationDataChecksumGenerator
     */
    public function __construct(
        ProductConfigurationConfig $productConfigurationConfig,
        ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface $productConfigurationDataChecksumGenerator
    ) {
        $this->productConfigurationDataChecksumGenerator = $productConfigurationDataChecksumGenerator;
        $this->productConfigurationConfig = $productConfigurationConfig;
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
        $encryptionKey = $this->productConfigurationConfig->getProductConfigurationEncryptionKey();

        $plainCopyOfConfiguredResponseData = $configuratorResponseData;

        $responseChecksum = $this->productConfigurationDataChecksumGenerator->generateProductConfigurationDataChecksum(
            $this->sanitizeConfiguratorResponseData($plainCopyOfConfiguredResponseData),
            $encryptionKey
        );

        if ($responseChecksum === $productConfiguratorResponseTransfer->getCheckSum()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $this->getErrorResponse(
            $productConfiguratorResponseProcessorResponseTransfer,
            static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_VALID_RESPONSE_CHECKSUM
        );
    }

    /**
     * @param array $configuratorResponseData
     *
     * @return array
     */
    protected function sanitizeConfiguratorResponseData(array $configuratorResponseData): array
    {
        unset($configuratorResponseData[ProductConfiguratorResponseTransfer::CHECK_SUM]);
        unset($configuratorResponseData[ProductConfiguratorResponseTransfer::TIMESTAMP]);

        return $configuratorResponseData;
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
