<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface;
use Spryker\Client\ProductConfiguration\ProductConfigurationConfig;

class ProductConfiguratorCheckSumResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_VALID_RESPONSE_CHECKSUM = 'product_configuration.validation.error.not_valid_response_checksum';

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface
     */
    protected $productConfigurationDataChecksumGenerator;

    /**
     * @var \Spryker\Client\ProductConfiguration\ProductConfigurationConfig
     */
    protected $productConfigurationConfig;

    /**
     * @param \Spryker\Client\ProductConfiguration\ProductConfigurationConfig $productConfigurationConfig
     * @param \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToProductConfigurationDataChecksumGeneratorInterface $productConfigurationDataChecksumGenerator
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
    public function validate(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer->requireProductConfiguratorResponse();

        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponse();
        $key = $this->productConfigurationConfig->getProductConfigurationEncryptionKey();

        $responseChecksum = $this->productConfigurationDataChecksumGenerator->generateProductConfigurationDataChecksum(
            $configuratorResponseData,
            $key
        );

        if ($responseChecksum === $productConfiguratorResponseTransfer->getCheckSum()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $productConfiguratorResponseProcessorResponseTransfer->addMessage((new MessageTransfer())
            ->setMessage(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_VALID_RESPONSE_CHECKSUM))
            ->setIsSuccessful(false);
    }
}
