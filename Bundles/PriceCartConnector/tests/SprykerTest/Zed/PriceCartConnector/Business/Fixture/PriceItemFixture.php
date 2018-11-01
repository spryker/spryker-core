<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use RuntimeException;
use Spryker\Shared\Calculation\Dependency\Transfer\PriceItemInterface;

class PriceItemFixture extends CartItemFixture implements PriceItemInterface
{
    /**
     * @var int
     */
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
     * @throws \RuntimeException
     *
     * @return int
     */
    public function getPriceToPay()
    {
        throw new RuntimeException('Implement getPriceToPay() method');
    }

    /**
     * @param int $priceToPay
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    public function setPriceToPay($priceToPay)
    {
        throw new RuntimeException('Implement setPriceToPay() method');
    }
}
