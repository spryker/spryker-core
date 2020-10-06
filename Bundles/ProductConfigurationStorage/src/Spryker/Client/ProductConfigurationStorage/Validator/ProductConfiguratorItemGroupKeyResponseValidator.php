<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConfig;

class ProductConfiguratorItemGroupKeyResponseValidator implements ProductConfiguratorResponseValidatorInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_STORAGE_GROUP_KEY_IS_NOT_PROVIDED = 'product_configuration_storage.validation.error.group_key_is_not_provided';

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

        if (
            $productConfiguratorResponseTransfer->getSourceType() === ProductConfigurationConfig::SOURCE_TYPE_CART &&
            !$productConfiguratorResponseTransfer->getItemGroupKey()
        ) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->addMessage(
                    (new MessageTransfer())
                        ->setMessage(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_STORAGE_GROUP_KEY_IS_NOT_PROVIDED)
                )->setIsSuccessful(false);
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
