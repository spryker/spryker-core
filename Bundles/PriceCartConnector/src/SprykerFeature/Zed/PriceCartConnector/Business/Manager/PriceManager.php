<?php

namespace SprykerFeature\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\Calculation\DependencyPriceItemInterfaceTransfer;
use Generated\Shared\Transfer\CartItemCollectionInterfaceTransfer;
use Generated\Shared\Transfer\CartItemInterfaceTransfer;
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
     * @param ItemCollectionInterface|ItemInterface[] $items
     *
     * @throws PriceMissingException
     * @return ItemCollectionInterface|ItemInterface[]
     */
    public function addGrossPriceToItems(ItemCollectionInterface $items)
    {
        /** @var PriceItemInterface|ItemInterface $cartItem */
        foreach ($items as $cartItem) {
            if (!$cartItem instanceof PriceItemInterface ||
                !$this->priceFacade->hasValidPrice($cartItem->getId(), $this->grossPriceType)) {
                throw new PriceMissingException(sprintf('Cart item %s can not be priced', $cartItem->getId()));
            }

            $cartItem->setGrossPrice($this->priceFacade->getPriceBySku($cartItem->getId(), $this->grossPriceType));
        }

        return $items;
    }
}
