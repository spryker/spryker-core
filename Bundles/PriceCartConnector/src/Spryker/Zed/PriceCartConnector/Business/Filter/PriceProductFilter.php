<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Filter;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceCartConnector\PriceCartConnectorConfig;

class PriceProductFilter implements PriceProductFilterInterface
{
    protected const ZERO_QUANTITY_VALUE = 0;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var string
     */
    protected $defaultPriceMode;

    /**
     * @var string
     */
    protected $currentCurrencyCode;

    /**
     * @var \Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[]
     */
    protected $cartItemQuantityCounterStrategyPlugins;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\CartItemQuantityCounterStrategyPluginInterface[] $cartItemQuantityCounterStrategyPlugins
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade,
        PriceCartConnectorToCurrencyFacadeInterface $currencyFacade,
        array $cartItemQuantityCounterStrategyPlugins
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->currencyFacade = $currencyFacade;
        $this->cartItemQuantityCounterStrategyPlugins = $cartItemQuantityCounterStrategyPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function createPriceProductFilterTransfer(
        CartChangeTransfer $cartChangeTransfer,
        ItemTransfer $itemTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer = $this->mapItemTransferToPriceProductFilterTransfer(
            new PriceProductFilterTransfer(),
            $cartChangeTransfer,
            $itemTransfer
        )
            ->setStoreName($this->findStoreName($cartChangeTransfer->getQuote()))
            ->setPriceMode($this->getPriceMode($cartChangeTransfer->getQuote()))
            ->setCurrencyIsoCode($this->getCurrencyCode($cartChangeTransfer->getQuote()))
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());

        if ($this->isPriceProductDimensionEnabled($priceProductFilterTransfer)) {
            $priceProductFilterTransfer->setQuote($cartChangeTransfer->getQuote());
        }

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getPriceMode(QuoteTransfer $quoteTransfer): string
    {
        if ($quoteTransfer->getPriceMode() === null) {
            if (!$this->defaultPriceMode) {
                $this->defaultPriceMode = $this->priceFacade->getDefaultPriceMode();
            }

            return $this->defaultPriceMode;
        }

        return $quoteTransfer->getPriceMode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string
     */
    protected function getCurrencyCode(QuoteTransfer $quoteTransfer): string
    {
        if ($quoteTransfer->getCurrency() === null || $quoteTransfer->getCurrency()->getCode() === null) {
            if (!$this->currentCurrencyCode) {
                $this->currentCurrencyCode = $this->currencyFacade->getCurrent()->getCode();
            }

            return $this->currentCurrencyCode;
        }

        return $quoteTransfer->getCurrency()->getCode();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string|null
     */
    protected function findStoreName(QuoteTransfer $quoteTransfer): ?string
    {
        if ($quoteTransfer->getStore() === null) {
            return null;
        }

        return $quoteTransfer->getStore()->getName();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return bool
     */
    protected function isPriceProductDimensionEnabled(PriceProductFilterTransfer $priceProductFilterTransfer): bool
    {
        return property_exists($priceProductFilterTransfer, 'quote');
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function mapItemTransferToPriceProductFilterTransfer(
        PriceProductFilterTransfer $priceProductFilterTransfer,
        CartChangeTransfer $cartChangeTransfer,
        ItemTransfer $itemTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer = $priceProductFilterTransfer
            ->fromArray($itemTransfer->toArray(), true)
            ->setQuantity($this->getItemTotalQuantity($itemTransfer, $cartChangeTransfer));

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int
     */
    protected function getItemTotalQuantity(ItemTransfer $itemTransfer, CartChangeTransfer $cartChangeTransfer): int
    {
        $quantity = $this->executeCartItemQuantityCounterStrategyPlugins($itemTransfer, $cartChangeTransfer);

        if ($quantity !== static::ZERO_QUANTITY_VALUE) {
            return $quantity;
        }

        foreach ($cartChangeTransfer->getQuote()->getItems() as $quoteItemTransfer) {
            if ($quoteItemTransfer->getSku() === $itemTransfer->getSku()) {
                $quantity += $quoteItemTransfer->getQuantity();
            }
        }

        foreach ($cartChangeTransfer->getItems() as $cartChangeItemTransfer) {
            if ($cartChangeItemTransfer->getSku() === $itemTransfer->getSku()) {
                if ($cartChangeTransfer->getOperation() === PriceCartConnectorConfig::OPERATION_REMOVE) {
                    $quantity -= $cartChangeItemTransfer->getQuantity();

                    continue;
                }

                $quantity += $cartChangeItemTransfer->getQuantity();
            }
        }

        return $quantity;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int
     */
    protected function executeCartItemQuantityCounterStrategyPlugins(
        ItemTransfer $itemTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): int {
        foreach ($this->cartItemQuantityCounterStrategyPlugins as $cartItemQuantityCounterStrategyPlugin) {
            if ($cartItemQuantityCounterStrategyPlugin->isApplicable($cartChangeTransfer, $itemTransfer)) {
                return $cartItemQuantityCounterStrategyPlugin
                    ->countCartItemQuantity($cartChangeTransfer, $itemTransfer)->getQuantity();
            }
        }

        return static::ZERO_QUANTITY_VALUE;
    }
}
