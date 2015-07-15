<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\PriceCartConnector\Business\Fixture;

use SprykerFeature\Shared\Calculation\Dependency\Transfer\PriceItemInterface;

class PriceItemFixture extends CartItemFixture implements PriceItemInterface
{

    private $price = 0;

    /**
     * @return int
     */
    public function getGrossPrice()
    {
        return $this->price;
    }

    /**
     * @param int $grossPrice
     *
     * @return $this
     */
    public function setGrossPrice($grossPrice)
    {
        $this->price = $grossPrice;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriceToPay()
    {
        // TODO: Implement getPriceToPay() method.
    }

    /**
     * @param int $priceToPay
     *
     * @return $this
     */
    public function setPriceToPay($priceToPay)
    {
        // TODO: Implement setPriceToPay() method.
    }

}
