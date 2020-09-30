<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;

class ProductConfiguratorMandatoryFieldsResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function validate(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer->requireProductConfiguratorResponse();

        $productConfiguratorResponseTransfer = $productConfiguratorResponseProcessorResponseTransfer
            ->getProductConfiguratorResponse();

        try {
            $this->assertMandatoryFields($productConfiguratorResponseTransfer);
        } catch (RequiredTransferPropertyException $requiredTransferPropertyException) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->addMessage(
                    (new MessageTransfer())
                        ->setMessage($requiredTransferPropertyException->getMessage())
                )->setIsSuccessful(false);
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return void
     */
    protected function assertMandatoryFields(ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer): void
    {
        $productConfiguratorResponseTransfer
            ->requireSku()
            ->requireSourceType()
            ->requireCheckSum()
            ->requireTimestamp()
            ->requireProductConfigurationInstance()
            ->getProductConfigurationInstance()
            ->requireConfiguratorKey();
    }
}
