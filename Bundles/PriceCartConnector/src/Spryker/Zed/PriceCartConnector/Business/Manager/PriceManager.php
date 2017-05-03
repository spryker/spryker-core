<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Price\PriceTaxMode;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;

class PriceManager implements PriceManagerInterface
{

    /**
     * @var \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface
     */
    private $priceFacade;

    /**
     * @var string
     */
    private $grossPriceType;

    /**
     * @param \Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface $priceFacade
     * @param string|null $grossPriceType
     */
    public function __construct(PriceCartToPriceInterface $priceFacade, $grossPriceType = null)
    {
        $this->priceFacade = $priceFacade;
        $this->grossPriceType = $grossPriceType;
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
        $cartChangeTransfer->setQuote($this->setQuoteTaxMode($cartChangeTransfer->getQuote()));

        foreach ($cartChangeTransfer->getItems() as $cartItem) {
            if (!$this->priceFacade->hasValidPrice($cartItem->getSku(), $this->grossPriceType)) {
                throw new PriceMissingException(sprintf('Cart item %s can not be priced', $cartItem->getSku()));
            }

            $cartItem->setUnitGrossPrice($this->priceFacade->getPriceBySku($cartItem->getSku(), $this->grossPriceType));
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function setQuoteTaxMode(QuoteTransfer $quoteTransfer)
    {
        $quoteTransfer->setTaxMode(PriceTaxMode::TAX_MODE_GROSS);

        return $quoteTransfer;
    }

}
