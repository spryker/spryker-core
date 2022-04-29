<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationCart\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationClientInterface;
use Spryker\Client\ProductConfigurationCart\Replacer\QuoteItemReplacerInterface;
use Spryker\Client\ProductConfigurationCart\Validator\ProductConfiguratorResponseValidatorInterface;

class ProductConfiguratorResponseProcessor implements ProductConfiguratorResponseProcessorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationClientInterface
     */
    protected $productConfigurationClient;

    /**
     * @var \Spryker\Client\ProductConfigurationCart\Validator\ProductConfiguratorResponseValidatorInterface
     */
    protected $productConfiguratorResponseValidator;

    /**
     * @var \Spryker\Client\ProductConfigurationCart\Replacer\QuoteItemReplacerInterface
     */
    protected $quoteItemReplacer;

    /**
     * @param \Spryker\Client\ProductConfigurationCart\Dependency\Client\ProductConfigurationCartToProductConfigurationClientInterface $productConfigurationClient
     * @param \Spryker\Client\ProductConfigurationCart\Validator\ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator
     * @param \Spryker\Client\ProductConfigurationCart\Replacer\QuoteItemReplacerInterface $quoteItemReplacer
     */
    public function __construct(
        ProductConfigurationCartToProductConfigurationClientInterface $productConfigurationClient,
        ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator,
        QuoteItemReplacerInterface $quoteItemReplacer
    ) {
        $this->productConfigurationClient = $productConfigurationClient;
        $this->productConfiguratorResponseValidator = $productConfiguratorResponseValidator;
        $this->quoteItemReplacer = $quoteItemReplacer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array<string, mixed> $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseTransfer = $this->productConfigurationClient->mapProductConfiguratorCheckSumResponse(
            $configuratorResponseData,
            $productConfiguratorResponseTransfer,
        );

        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setIsSuccessful(true)
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer);

        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfiguratorResponseValidator->validateProductConfiguratorCheckSumResponse(
            $productConfiguratorResponseProcessorResponseTransfer,
            $configuratorResponseData,
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $this->quoteItemReplacer->replaceItemInQuote($productConfiguratorResponseProcessorResponseTransfer);
    }
}
