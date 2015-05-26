<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\PriceItemInterface;
use Generated\Shared\Cart\CartItemsInterface;
use Generated\Shared\Cart\CartItemInterface;
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
     * @param CartItemsInterface|CartItemInterface[] $items
     *
     * @throws PriceMissingException
     * @return CartItemsInterface|CartItemInterface[]
     */
    public function addGrossPriceToItems(CartItemsInterface $items)
    {
        /** @var PriceItemInterface|CartItemInterface $cartItem */
        foreach ($items->getCartItems() as $cartItem) {
            if (!$cartItem instanceof PriceItemInterface ||
                !$this->priceFacade->hasValidPrice($cartItem->getId(), $this->grossPriceType)) {
                throw new PriceMissingException(sprintf('Cart item %s can not be priced', $cartItem->getId()));
            }

            $cartItem->setGrossPrice($this->priceFacade->getPriceBySku($cartItem->getId(), $this->grossPriceType));
        }

        return $items;
    }
}
