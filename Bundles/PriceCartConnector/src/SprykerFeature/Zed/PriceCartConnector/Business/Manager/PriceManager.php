<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use SprykerFeature\Zed\Price\Business\PriceFacade;
use SprykerFeature\Zed\PriceCartConnector\Business\Exception\PriceMissingException;

class PriceManager implements PriceManagerInterface
{

    /**
     * @var PriceFacade
     */
    private $priceFacade;

    /**
     * @var string
     */
    private $grossPriceType;

    /**
     * @param PriceFacade $priceFacade
     * @param null $grossPriceType
     */
    public function __construct(PriceFacade $priceFacade, $grossPriceType = null)
    {
        $this->priceFacade = $priceFacade;
        $this->grossPriceType = $grossPriceType;
    }

    /**
     * @param ChangeTransfer $change
     *
     * @throws PriceMissingException
     *
     * @return ItemTransfer[]
     */
    public function addGrossPriceToItems(ChangeTransfer $change)
    {
        foreach ($change->getItems() as $cartItem) {
            if (!$this->priceFacade->hasValidPrice($cartItem->getSku(), $this->grossPriceType)) {
                throw new PriceMissingException(sprintf('Cart item %s can not be priced', $cartItem->getSku()));
            }

            $cartItem->setGrossPrice($this->priceFacade->getPriceBySku($cartItem->getSku(), $this->grossPriceType));
        }

        return $change;
    }

}
