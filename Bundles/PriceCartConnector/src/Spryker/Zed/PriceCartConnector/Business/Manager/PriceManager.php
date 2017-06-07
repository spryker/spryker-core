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
    protected $grossPriceType;

    /**
     * @var string
     */
    protected $priceMode;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param string|null $grossPriceType
     * @param string $priceMode
     */
    public function __construct(
        PriceCartToPriceInterface $priceFacade,
        $grossPriceType,
        $priceMode = PriceMode::PRICE_MODE_GROSS
    ) {
        $this->priceFacade = $priceFacade;
        $this->grossPriceType = $grossPriceType;
        $this->priceMode = $priceMode;
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

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->priceFacade->hasValidPrice($itemTransfer->getSku(), $this->grossPriceType)) {
                throw new PriceMissingException(
                    sprintf(
                        'Cart item %s can not be priced',
                        $itemTransfer->getSku()
                    )
                );
            }

            $this->setPrice($itemTransfer, $cartChangeTransfer->getQuote()->getPriceMode());
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
        $quoteTransfer->setPriceMode($this->priceMode);

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $priceMode
     *
     * @return void
     */
    protected function setPrice(ItemTransfer $itemTransfer, $priceMode)
    {
        if ($priceMode === PriceMode::PRICE_MODE_NET) {
            $itemTransfer->setUnitNetPrice(
                $this->priceFacade->getPriceBySku(
                    $itemTransfer->getSku(),
                    $this->grossPriceType
                )
            );
            $itemTransfer->setUnitGrossPrice(0);
        } else {
            $itemTransfer->setUnitGrossPrice(
                $this->priceFacade->getPriceBySku(
                    $itemTransfer->getSku(),
                    $this->grossPriceType
                )
            );
            $itemTransfer->setUnitNetPrice(0);
        }
    }

}
