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
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface $currencyFacade
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade,
        PriceCartConnectorToCurrencyFacadeInterface $currencyFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
        $this->currencyFacade = $currencyFacade;
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
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int
     */
    protected function getItemTotalQuantity(ItemTransfer $cartChangeItemTransfer, CartChangeTransfer $cartChangeTransfer): int
    {
        $quantity = 0;
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $cartChangeItemTransfer->getSku()) {
                $quantity += $itemTransfer->getQuantity();
            }
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $cartChangeItemTransfer->getSku()) {
                if ($cartChangeTransfer->getOperation() === PriceCartConnectorConfig::OPERATION_REMOVE) {
                    $quantity -= $itemTransfer->getQuantity();
                    continue;
                }

                $quantity += $itemTransfer->getQuantity();
            }
        }

        return $quantity;
    }
}
