<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\Price\Business\PriceFacade;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToProductBridge;

class PriceManager implements PriceManagerInterface
{

    /**
     * @var PriceCartToPriceInterface
     */
    private $priceFacade;

    /**
     * @var string
     */
    private $grossPriceType;

    /**
     * @param PriceCartToPriceInterface $priceFacade
     * @param null $grossPriceType
     */
    public function __construct(PriceCartToPriceInterface $priceFacade, $grossPriceType = null)
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
