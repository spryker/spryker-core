<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToProductConfigurationDataChecksumGeneratorInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;

class ProductConfiguratorCheckSumResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_VALID_RESPONSE_CHECKSUM = 'product_configuration.validation.error.not_valid_response_checksum';

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig
     */
    protected $config;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToProductConfigurationDataChecksumGeneratorInterface
     */
    protected $productConfigurationDataChecksumGenerator;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig $config
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Service\ProductConfigurationStorageToProductConfigurationDataChecksumGeneratorInterface $productConfigurationDataChecksumGenerator
     */
    public function __construct(
        ProductConfigurationStorageConfig $config,
        ProductConfigurationStorageToProductConfigurationDataChecksumGeneratorInterface $productConfigurationDataChecksumGenerator
    ) {
        $this->config = $config;
        $this->productConfigurationDataChecksumGenerator = $productConfigurationDataChecksumGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
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
        $key = $this->config->getProductConfigurationEncryptionKey();

        $responseChecksum = $this->productConfigurationDataChecksumGenerator->generateProductConfigurationDataChecksum(
            $productConfiguratorResponseTransfer->toArray(),
            $key
        );

        if ($responseChecksum !== $productConfiguratorResponseTransfer->getCheckSum()) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->addMessage(
                    (new MessageTransfer())
                        ->setMessage(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_NOT_VALID_RESPONSE_CHECKSUM)
                )->setIsSuccessful(false);
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
