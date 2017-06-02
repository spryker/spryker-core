<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Price\PriceMode;
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

            $itemTransfer->setUnitGrossPrice(
                $this->priceFacade->getPriceBySku(
                    $itemTransfer->getSku(),
                    $this->grossPriceType
                )
            );
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
        $quoteTransfer->setPriceMode(PriceMode::PRICE_MODE_GROSS);

        return $quoteTransfer;
    }

}
