<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Processor;

use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
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
    protected $validators;

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface
     */
    protected $productConfigurationInstanceMapper;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Writer\ProductConfigurationInstanceWriterInterface $productConfigurationInstanceWriter
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface $cartClient
     * @param \Spryker\Client\ProductConfigurationStorage\Mapper\ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper
     * @param \Spryker\Client\ProductConfigurationStorage\Validator\ProductConfiguratorResponseValidatorInterface[] $validators
     */
    public function __construct(
        ProductConfigurationInstanceWriterInterface $productConfigurationInstanceWriter,
        ProductConfigurationStorageToCartClientInterface $cartClient,
        ProductConfigurationInstanceMapperInterface $productConfigurationInstanceMapper,
        array $validators
    ) {

        $this->productConfigurationInstanceWriter = $productConfigurationInstanceWriter;
        $this->cartClient = $cartClient;
        $this->validators = $validators;
        $this->productConfigurationInstanceMapper = $productConfigurationInstanceMapper;
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
            $productConfiguratorResponseTransfer
        );

        if (!$productConfiguratorResponseProcessorResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer;
        }

        $productConfigurationInstance = $productConfiguratorResponseTransfer->getProductConfigurationInstance();

        $productConfigurationInstance = $this->productConfigurationInstanceMapper->
            mapProductConfigurationInstancePricesToProductConfigurationInstancePriceProductTransfer(
                $productConfigurationInstance
            );

        $productConfiguratorResponseTransfer->setProductConfigurationInstance($productConfigurationInstance);

        $this->storeProductConfigurationInstance($productConfiguratorResponseTransfer);

        return $this->replaceItemInQuote(
            $productConfiguratorResponseTransfer,
            $productConfiguratorResponseProcessorResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function validateResponse(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $productConfiguratorResponseProcessorResponseTransfer = (new ProductConfiguratorResponseProcessorResponseTransfer())
            ->setProductConfiguratorResponse($productConfiguratorResponseTransfer)
            ->setIsSuccessful(true);

        foreach ($this->validators as $validator) {
            $productConfiguratorResponseProcessorResponseTransfer = $validator->validate(
                $productConfiguratorResponseProcessorResponseTransfer
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

        return $productConfiguratorResponseProcessorResponseTransfer
            ->setIsSuccessful($quoteResponseTransfer->getIsSuccessful());
    }
}
