<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Processor;

use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstancePriceMapperInterface;
use Spryker\Client\ProductConfigurationStorage\ProductConfigurationStorageConfig;
use Spryker\Client\ProductConfigurationStorage\Replacer\QuoteItemReplacerInterface;
use Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface;
use Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface;

class ProductConfiguratorCheckSumResponseProcessor implements ProductConfiguratorCheckSumResponseProcessorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface
     */
    protected $productConfigurationInstanceWriter;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstancePriceMapperInterface
     */
    protected $productConfigurationInstancePriceMapper;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Replacer\QuoteItemReplacerInterface
     */
    protected $quoteItemReplacer;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface
     */
    protected $productConfiguratorResponseValidator;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface $productConfigurationInstanceWriter
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper
     * @param \Spryker\Client\ProductConfigurationStorage\Replacer\QuoteItemReplacerInterface $quoteItemReplacer
     * @param \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator
     */
    public function __construct(
        ProductConfigurationInstanceWriterInterface $productConfigurationInstanceWriter,
        ProductConfigurationInstancePriceMapperInterface $productConfigurationInstancePriceMapper,
        QuoteItemReplacerInterface $quoteItemReplacer,
        ProductConfiguratorResponseValidatorInterface $productConfiguratorResponseValidator
    ) {
        $this->productConfigurationInstanceWriter = $productConfigurationInstanceWriter;
        $this->productConfigurationInstancePriceMapper = $productConfigurationInstancePriceMapper;
        $this->quoteItemReplacer = $quoteItemReplacer;
        $this->productConfiguratorResponseValidator = $productConfiguratorResponseValidator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function processProductConfiguratorCheckSumResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer)
            ->setIsSuccessful(true);

        $productConfiguratorResponseProcessorResponseTransfer = $this->productConfiguratorResponseValidator->validate(
            $productConfiguratorResponseProcessorResponseTransfer,
            $configuratorResponseData
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $productConfiguratorResponseTransfer = $this->mapProductConfigurationInstancePrices(
            $productConfiguratorResponseTransfer,
            $configuratorResponseData
        );

        $this->storeProductConfigurationInstance($productConfiguratorResponseTransfer);

        if ($productConfiguratorResponseTransfer->getSourceType() !== ProductConfigurationStorageConfig::SOURCE_TYPE_CART) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        return $this->quoteItemReplacer->replaceItemInQuote(
            $productConfiguratorResponseTransfer,
            $productConfiguratorResponseProcessorResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return void
     */
    protected function storeProductConfigurationInstance(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): void {
        if ($productConfiguratorResponseTransfer->getSourceType() !== ProductConfigurationStorageConfig::SOURCE_TYPE_PDP) {
            return;
        }

        $this->productConfigurationInstanceWriter->storeProductConfigurationInstanceBySku(
            $productConfiguratorResponseTransfer->getSku(),
            $productConfiguratorResponseTransfer->getProductConfigurationInstance()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer
     */
    protected function mapProductConfigurationInstancePrices(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseTransfer {
        $productConfigurationInstanceTransfer = $productConfiguratorResponseTransfer->getProductConfigurationInstanceOrFail();

        $productConfigurationInstanceTransfer = $this->productConfigurationInstancePriceMapper
            ->mapConfiguratorResponseDataPricesToProductConfigurationInstancePrices(
                $configuratorResponseData,
                $productConfigurationInstanceTransfer
            );

        return $productConfiguratorResponseTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);
    }
}
