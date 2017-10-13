<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Price\PriceMode;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;

class PriceManager implements PriceManagerInterface
{

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    protected $priceFacade;

    /**
     * @var string
     */
    protected $priceType;

    /**
     * @var string
     */
    protected $defaultPriceMode;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param string|null $priceType
     * @param string $priceMode
     */
    public function __construct(
        PriceCartToPriceInterface $priceFacade,
        $priceType,
        $priceMode = PriceMode::PRICE_MODE_GROSS
    ) {
        $this->priceFacade = $priceFacade;
        $this->priceType = $priceType;
        $this->defaultPriceMode = $priceMode;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function addGrossPriceToItems(CartChangeTransfer $cartChangeTransfer)
    {
        $cartChangeTransfer->setQuote($this->setQuotePriceMode($cartChangeTransfer->getQuote()));

        $priceMode = $cartChangeTransfer->getQuote()->getPriceMode();
        $currencyIsoCode = $cartChangeTransfer->getQuote()->getCurrency()->getCode();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->priceFacade->hasValidPrice($itemTransfer->getSku(), $this->priceType, $currencyIsoCode, $priceMode)) {
                throw new PriceMissingException(
                    sprintf(
                        'Cart item %s can not be priced',
                        $itemTransfer->getSku()
                    )
                );
            }

            $this->setPrice($itemTransfer, $priceMode, $currencyIsoCode);
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuotePriceMode(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->setPriceMode($this->defaultPriceMode);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     * @param string $currencyIsoCode
     *
     * @return void
     */
    protected function setPrice(ItemTransfer $itemTransfer, $priceMode, $currencyIsoCode)
    {
        if ($priceMode === PriceMode::PRICE_MODE_NET) {
            $itemTransfer->setUnitNetPrice(
                $this->priceFacade->getPriceBySku(
                    $itemTransfer->getSku(),
                    $this->priceType,
                    $currencyIsoCode,
                    $priceMode
                )
            );
            $itemTransfer->setUnitGrossPrice(0);
            return;
        }

        $itemTransfer->setUnitGrossPrice(
            $this->priceFacade->getPriceBySku(
                $itemTransfer->getSku(),
                $this->priceType,
                $currencyIsoCode,
                $priceMode
            )
        );
        $itemTransfer->setUnitNetPrice(0);
    }

}
