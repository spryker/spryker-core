<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\Log\LoggerTrait;

class ProductConfiguratorMandatoryFieldsResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    use LoggerTrait;

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR = 'product_configuration.response.validation.error';

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validateProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer->requireProductConfiguratorResponse();

        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponseOrFail();

        try {
            $this->assertMandatoryFields($productConfiguratorResponseTransfer);
        } catch (RequiredTransferPropertyException $requiredTransferPropertyException) {
            $this->getLogger()->error(
                static::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR,
                ['exception' => $requiredTransferPropertyException],
            );

            return $this->addErrorToResponse(
                $productConfiguratorResponseProcessorResponseTransfer,
                static::GLOSSARY_KEY_RESPONSE_VALIDATION_ERROR,
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
    protected function addErrorToResponse(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        string $errorMessage
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        return $productConfiguratorResponseProcessorResponseTransfer
            ->addMessage((new MessageTransfer())->setValue($errorMessage))
            ->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return void
     */
    protected function assertMandatoryFields(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): void
    {
        $productConfiguratorResponseTransfer
            ->requireSourceType()
            ->requireCheckSum()
            ->requireTimestamp()
            ->requireProductConfigurationInstance()
            ->getProductConfigurationInstanceOrFail()
                ->requireConfiguratorKey();
    }
}
