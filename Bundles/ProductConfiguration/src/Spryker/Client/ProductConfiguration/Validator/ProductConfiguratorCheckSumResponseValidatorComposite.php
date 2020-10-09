<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Validator;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;

class ProductConfiguratorCheckSumResponseValidatorComposite implements ProductConfiguratorResponseValidatorInterface
{
    /**
     * @var \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface[]
     */
    protected $productConfiguratorResponseValidators;

    /**
     * @param \Spryker\Client\ProductConfiguration\Validator\ProductConfiguratorResponseValidatorInterface[] $productConfiguratorResponseValidators
     */
    public function __construct(array $productConfiguratorResponseValidators)
    {
        $this->productConfiguratorResponseValidators = $productConfiguratorResponseValidators;
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
        foreach ($this->productConfiguratorResponseValidators as $productConfiguratorResponseValidator) {
            $productConfiguratorResponseProcessorResponseTransfer = $productConfiguratorResponseValidator->validate(
                $productConfiguratorResponseProcessorResponseTransfer,
                $configuratorResponseData
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }
}
