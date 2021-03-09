<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationStorage\Replacer;

use Generated\Shared\Transfer\ItemReplaceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorResponseTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface;

class QuoteItemReplacer implements QuoteItemReplacerInterface
{
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_ITEM_NOT_FOUND_IN_CART = 'product_configuration.error.configured_item_not_found_in_cart';
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_AVAILABILITY_FAILED = 'product_configuration.error.availability.failed';

    protected const MESSAGE_TYPE_ERROR = 'error';

    protected const TRANSLATION_PARAMETER_SKU = '%sku%';
    protected const TRANSLATION_PARAMETER_STOCK = '%stock%';

    /**
     * @var \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @param \Spryker\Client\ProductConfigurationStorage\Dependency\Client\ProductConfigurationStorageToCartClientInterface $cartClient
     */
    public function __construct(ProductConfigurationStorageToCartClientInterface $cartClient)
    {
        $this->cartClient = $cartClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    public function replaceItemInQuote(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        $quoteTransfer = $this->cartClient->getQuote();
        $itemToBeReplacedTransfer = $this->cartClient->findQuoteItem(
            $quoteTransfer,
            $productConfiguratorResponseTransfer->getSku(),
            $productConfiguratorResponseTransfer->getItemGroupKey()
        );

        if (!$itemToBeReplacedTransfer) {
            return $productConfiguratorResponseProcessorResponseTransfer
                ->setIsSuccessful(false)
                ->addMessage($this->createConfigurationItemNotFoundMessage($productConfiguratorResponseTransfer->getSku()));
        }

        $itemReplaceTransfer = $this->createItemReplaceTransfer(
            $productConfiguratorResponseTransfer,
            $itemToBeReplacedTransfer,
            $quoteTransfer
        );

        $productConfiguratorResponseProcessorResponseTransfer = $this->handleChangedQuantityError(
            $productConfiguratorResponseProcessorResponseTransfer,
            $itemReplaceTransfer
        );

        $quoteResponseTransfer = $this->cartClient->replaceItem($itemReplaceTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful(true);
        }

        return $this->addQuoteErrors(
            $quoteResponseTransfer,
            $productConfiguratorResponseProcessorResponseTransfer
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemToBeReplacedTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemReplaceTransfer
     */
    protected function createItemReplaceTransfer(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        ItemTransfer $itemToBeReplacedTransfer,
        QuoteTransfer $quoteTransfer
    ): ItemReplaceTransfer {
        $newItemTransfer = (new ItemTransfer())
            ->fromArray($itemToBeReplacedTransfer->toArray())
            ->setGroupKey(null)
            ->setProductConfigurationInstance($productConfiguratorResponseTransfer->getProductConfigurationInstance());

        $newItemTransfer = $this->replaceItemQuantity($productConfiguratorResponseTransfer, $newItemTransfer);

        return (new ItemReplaceTransfer())
            ->setItemToBeReplaced($itemToBeReplacedTransfer)
            ->setNewItem($newItemTransfer)
            ->setQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function replaceItemQuantity(
        ProductConfiguratorResponseTransfer $productConfiguratorResponseTransfer,
        ItemTransfer $itemTransfer
    ): ItemTransfer {
        $productConfigurationInstanceTransfer = $productConfiguratorResponseTransfer->getProductConfigurationInstance();

        if (!$productConfigurationInstanceTransfer) {
            return $itemTransfer;
        }

        $availableQuantity = $productConfigurationInstanceTransfer->getAvailableQuantity();

        if ($availableQuantity && $availableQuantity < $itemTransfer->getQuantity()) {
            $itemTransfer->setQuantity($availableQuantity);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function addQuoteErrors(
        QuoteResponseTransfer $quoteResponseTransfer,
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        foreach ($quoteResponseTransfer->getErrors() as $error) {
            $productConfiguratorResponseProcessorResponseTransfer->addMessage(
                (new MessageTransfer())->setValue($error->getMessage())
            );
        }

        return $productConfiguratorResponseProcessorResponseTransfer->setIsSuccessful(false);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer
     * @param \Generated\Shared\Transfer\ItemReplaceTransfer $itemReplaceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorResponseProcessorResponseTransfer
     */
    protected function handleChangedQuantityError(
        ProductConfiguratorResponseProcessorResponseTransfer $productConfiguratorResponseProcessorResponseTransfer,
        ItemReplaceTransfer $itemReplaceTransfer
    ): ProductConfiguratorResponseProcessorResponseTransfer {
        if ($itemReplaceTransfer->getItemToBeReplaced()->getQuantity() != $itemReplaceTransfer->getNewItem()->getQuantity()) {
            $productConfiguratorResponseProcessorResponseTransfer
                ->addMessage(
                    $this->createConfigurationOnlyHasAvailabilityMessage(
                        $itemReplaceTransfer->getNewItem()->getQuantity()
                    )
                );
        }

        return $productConfiguratorResponseProcessorResponseTransfer;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createConfigurationItemNotFoundMessage(string $sku): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_ITEM_NOT_FOUND_IN_CART)
            ->setParameters([static::TRANSLATION_PARAMETER_SKU => $sku]);
    }

    /**
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createConfigurationOnlyHasAvailabilityMessage(int $quantity): MessageTransfer
    {
        return (new MessageTransfer())
            ->setType(static::MESSAGE_TYPE_ERROR)
            ->setValue(static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_AVAILABILITY_FAILED)
            ->setParameters([
                static::TRANSLATION_PARAMETER_STOCK => $quantity,
            ]);
    }
}
