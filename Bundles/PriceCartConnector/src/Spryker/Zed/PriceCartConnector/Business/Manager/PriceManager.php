<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceCartConnector\Business\Manager;

use Generated\Shared\Transfer\ChangeTransfer;
use Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException;
use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceInterface;
use Spryker\Zed\Price\Business\PriceFacade;

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
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @throws \Spryker\Zed\PriceCartConnector\Business\Exception\PriceMissingException
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
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
