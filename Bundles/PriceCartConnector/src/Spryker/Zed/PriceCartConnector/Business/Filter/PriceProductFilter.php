<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Filter;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartConnectorToCurrencyFacadeInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;

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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    public function createPriceProductFilterTransfer(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer = $this->mapItemTransferToPriceProductFilterTransfer(
            new PriceProductFilterTransfer(),
            $quoteTransfer,
            $itemTransfer
        )
            ->setStoreName($this->findStoreName($quoteTransfer))
            ->setPriceMode($this->getPriceMode($quoteTransfer))
            ->setCurrencyIsoCode($this->getCurrencyCode($quoteTransfer))
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());

        if ($this->isPriceProductDimensionEnabled($priceProductFilterTransfer)) {
            $priceProductFilterTransfer->setQuote($quoteTransfer);
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
            return $this->priceFacade->getDefaultPriceMode();
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
            return $this->currencyFacade->getCurrent()->getCode();
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
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function mapItemTransferToPriceProductFilterTransfer(
        PriceProductFilterTransfer $priceProductFilterTransfer,
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer
    ): PriceProductFilterTransfer {
        $priceProductFilterTransfer = $priceProductFilterTransfer->fromArray($itemTransfer->toArray(), true);

        $itemInQuote = $this->getItemInQuote($itemTransfer, $quoteTransfer);
        if ($itemInQuote !== null) {
            $priceProductFilterTransfer->setQuantity($itemTransfer->getQuantity() + $itemInQuote->getQuantity());
        }

        return $priceProductFilterTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $cartChangeItemTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer|null
     */
    protected function getItemInQuote(ItemTransfer $cartChangeItemTransfer, QuoteTransfer $quoteTransfer): ?ItemTransfer
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku() === $cartChangeItemTransfer->getSku()) {
                return $itemTransfer;
            }
        }

        return null;
    }
}
