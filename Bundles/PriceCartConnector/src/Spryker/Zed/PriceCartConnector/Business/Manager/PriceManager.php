<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;

class PriceManager implements PriceManagerInterface
{
    /**
     * @var string
     */
    protected static $netPriceModeIdentifier;

    /**
     * @var string
     */
    protected static $defaultPriceType;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface
     */
    protected $priceProductFacade;

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface $priceProductFacade
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     */
    public function __construct(
        PriceCartToPriceProductInterface $priceProductFacade,
        PriceCartToPriceInterface $priceFacade
    ) {
        $this->priceProductFacade = $priceProductFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addPriceToItems(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->setQuote(
            $this->setQuotePriceMode($cartChangeTransfer->getQuote())
        );

        $priceMode = $cartChangeTransfer->getQuote()->getPriceMode();
        $currencyIsoCode = $cartChangeTransfer->getQuote()->getCurrency()->getCode();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->setOriginUnitPrices($itemTransfer, $priceMode, $currencyIsoCode);

            if ($this->hasForcedUnitGrossPrice($itemTransfer)) {
                continue;
            }

            if ($this->hasSourceUnitPrices($itemTransfer)) {
                $this->applySourceUnitPrices($itemTransfer);
                continue;
            }

            $this->applyOriginUnitPrices($itemTransfer);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function setOriginUnitPrices(ItemTransfer $itemTransfer, $priceMode, $currencyIsoCode)
    {
        $priceProductFilterTransfer = $this->createPriceProductFilter($itemTransfer, $priceMode, $currencyIsoCode);
        $this->setPrice($itemTransfer, $priceProductFilterTransfer, $priceMode);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applySourceUnitPrices(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getSourceUnitNetPrice() !== null) {
            $itemTransfer->setUnitNetPrice($itemTransfer->getSourceUnitNetPrice());
            $itemTransfer->setUnitGrossPrice(0);
        }

        if ($itemTransfer->getSourceUnitGrossPrice() !== null) {
            $itemTransfer->setUnitNetPrice(0);
            $itemTransfer->setUnitGrossPrice($itemTransfer->getSourceUnitGrossPrice());
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function applyOriginUnitPrices(ItemTransfer $itemTransfer)
    {
        $itemTransfer->setUnitNetPrice($itemTransfer->getOriginUnitNetPrice());
        $itemTransfer->setUnitGrossPrice($itemTransfer->getOriginUnitGrossPrice());

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasSourceUnitPrices(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getSourceUnitGrossPrice() !== null) {
            return true;
        }

        if ($itemTransfer->getSourceUnitNetPrice() !== null) {
            return true;
        }

        return false;
    }

    /**
     * @deprecated Will be removed with a next major release
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function hasForcedUnitGrossPrice(ItemTransfer $itemTransfer)
    {
        if ($itemTransfer->getForcedUnitGrossPrice() && $itemTransfer->getUnitGrossPrice() !== null) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuotePriceMode(QuoteTransfer $quoteTransfer)
    {
        if (!$quoteTransfer->getPriceMode()) {
            $quoteTransfer->setPriceMode($this->priceFacade->getDefaultPriceMode());
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     * @param string $priceMode
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     *
     * @return void
     */
    protected function setPrice(
        ItemTransfer $itemTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer,
        $priceMode
    ) {
        $price = $this->priceProductFacade->findPriceFor($priceProductFilterTransfer);

        if ($price === null) {
            throw new PriceMissingException(
                sprintf(
                    'Cart item "%s" can not be priced.',
                    $itemTransfer->getSku()
                )
            );
        }

        if ($priceMode === $this->getNetPriceModeIdentifier()) {
            $itemTransfer->setOriginUnitNetPrice($price);
            $itemTransfer->setOriginUnitGrossPrice(0);
            $itemTransfer->setSumGrossPrice(0);
            return;
        }

        $itemTransfer->setOriginUnitNetPrice(0);
        $itemTransfer->setOriginUnitGrossPrice($price);
        $itemTransfer->setSumNetPrice(0);
    }

    /**
     * @return string
     */
    protected function getNetPriceModeIdentifier()
    {
        if (!static::$netPriceModeIdentifier) {
            static::$netPriceModeIdentifier = $this->priceFacade->getNetPriceModeIdentifier();
        }

        return static::$netPriceModeIdentifier;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     * @param string $currencyIsoCode
     *
     * @return \Generated\Shared\Transfer\PriceProductFilterTransfer
     */
    protected function createPriceProductFilter(ItemTransfer $itemTransfer, $priceMode, $currencyIsoCode)
    {
        return (new PriceProductFilterTransfer())
            ->setPriceMode($priceMode)
            ->setCurrencyIsoCode($currencyIsoCode)
            ->setSku($itemTransfer->getSku())
            ->setPriceTypeName($this->priceProductFacade->getDefaultPriceTypeName());
    }
}
