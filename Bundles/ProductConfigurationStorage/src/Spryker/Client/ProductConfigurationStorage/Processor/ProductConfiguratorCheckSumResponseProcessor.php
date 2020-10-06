<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Processor;

use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface;
use Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface;
use Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConfig;

class ProductConfiguratorCheckSumResponseProcessor implements ProductConfiguratorCheckSumResponseProcessorInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface
     */
    protected $productConfigurationInstanceWriter;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface[]
     */
    protected $productConfiguratorResponseValidators;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface
     */
    protected $productConfigurationInstanceMapper;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface $productConfigurationInstanceWriter
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface $cartClient
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper
     * @param \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface[] $productConfiguratorResponseValidators
     */
    public function __construct(
        ProductConfigurationInstanceWriterInterface $productConfigurationInstanceWriter,
        ProductConfigurationStorageToCartClientInterface $cartClient,
        ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper,
        array $productConfiguratorResponseValidators
    ) {
        $this->productConfigurationInstanceWriter = $productConfigurationInstanceWriter;
        $this->cartClient = $cartClient;
        $this->productConfigurationInstanceMapper = $productConfigurationInstanceMapper;
        $this->productConfiguratorResponseValidators = $productConfiguratorResponseValidators;
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
        $productConfiguratorResponseProcessorResponseTransfer = $this->validateResponse(
            $productConfiguratorResponseTransfer,
            $configuratorResponseData
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $productConfigurationInstanceTransfer = $productConfiguratorResponseTransfer->getProductConfigurationInstance();

        $productConfigurationInstanceTransfer = $this->productConfigurationInstanceMapper
            ->mapProductConfigurationInstancePricesToProductConfigurationInstancePriceProductTransfer(
                $productConfigurationInstanceTransfer
            );

        $productConfiguratorResponseTransfer->setProductConfigurationInstance($productConfigurationInstanceTransfer);

        $this->storeProductConfigurationInstance($productConfiguratorResponseTransfer);

        return $this->replaceItemInQuote(
            $productConfiguratorResponseTransfer,
            $productConfiguratorResponseProcessorResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param array $configuratorResponseData
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function validateResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        array $configuratorResponseData
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer)
            ->setIsSuccessful(true);

        foreach ($this->productConfiguratorResponseValidators as $productConfiguratorResponseValidator) {
            $productConfiguratorResponseProcessorResponseTransfer = $productConfiguratorResponseValidator->validate(
                $productConfiguratorResponseProcessorResponseTransfer,
                $configuratorResponseData
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return void
     */
    protected function storeProductConfigurationInstance(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): void {
        if ($productConfiguratorResponseTransfer->getSourceType() !== ProductConfigurationConfig::SOURCE_TYPE_PDP) {
            return;
        }

        $this->productConfigurationInstanceWriter->storeProductConfigurationInstanceBySku(
            $productConfiguratorResponseTransfer->getSku(),
            $productConfiguratorResponseTransfer->getProductConfigurationInstance()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function replaceItemInQuote(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        if ($productConfiguratorResponseTransfer->getSourceType() !== ProductConfigurationConfig::SOURCE_TYPE_CART) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $quoteTransfer = $this->cartClient->getQuote();
        $itemToBeReplacedTransfer = $this->cartClient->findQuoteItem(
            $quoteTransfer,
            $productConfiguratorResponseTransfer->getSku(),
            $productConfiguratorResponseTransfer->getItemGroupKey()
        );

        if ($itemToBeReplacedTransfer) {
            $newItemTransfer = (new ItemTransfer())
                ->fromArray(
                    $itemToBeReplacedTransfer->toArray()
                )->setProductConfigurationInstance(
                    $productConfiguratorResponseTransfer->getProductConfigurationInstance()
                );
        }

        $itemReplaceTransfer = (new ItemReplaceTransfer())
            ->setItemToBeReplaced($itemToBeReplacedTransfer)
            ->setNewItem($newItemTransfer ?? null)
            ->setQuote($quoteTransfer);

        $quoteResponseTransfer = $this->cartClient->replaceItem($itemReplaceTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful(true);
        }

        foreach ($quoteResponseTransfer->getErrors() as $error) {
            $productConfiguratorResponseProcessorResponseTransfer->addMessage(
                (new MessageTransfer())->setMessage($error->getMessage())
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful(false);
    }
}
