<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Validator;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductConfigurationClientInterface;

class ProductConfiguratorCheckSumResponseValidatorComposite implements ProductConfiguratorResponseValidatorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface[]
     */
    protected $productConfiguratorResponseValidators;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToProductConfigurationClientInterface $productConfigurationClient
     * @param \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface[] $productConfiguratorResponseValidators
     */
    public function __construct(
        ProductConfigurationStorageToProductConfigurationClientInterface $productConfigurationClient,
        array $productConfiguratorResponseValidators
    ) {
        $this->productConfiguratorResponseValidators = $productConfiguratorResponseValidators;
        $this->productConfigurationClient = $productConfigurationClient;
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
        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfigurationClient
            ->validateProductConfiguratorCheckSumResponse(
                $productConfiguratorResponseProcessorResponseTransfer,
                $configuratorResponseData
            );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        foreach ($this->productConfiguratorResponseValidators as $productConfiguratorResponseValidator) {
            $productConfiguratorResponseProcessorResponseTransfer = $productConfiguratorResponseValidator->validate(
                $productConfiguratorResponseProcessorResponseTransfer,
                $configuratorResponseData
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
