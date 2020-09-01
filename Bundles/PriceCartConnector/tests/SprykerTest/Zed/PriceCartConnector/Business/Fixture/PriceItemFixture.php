<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\PriceCartConnector\Business\Fixture;

use RuntimeException;

class PriceItemFixture extends CartItemFixture
{
    /**
     * @var int
     */
    protected $price = 0;

    /**
     * @return int
     */
    public function getGrossPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $grossPrice
     *
     * @return $this
     */
    public function setGrossPrice(int $grossPrice)
    {
        $this->price = $grossPrice;

        return $this;
    }

    /**
     * @throws \RuntimeException
     *
     * @return int
     */
    public function getPriceToPay(): int
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
    public function setPriceToPay(int $priceToPay)
    {
        throw new RuntimeException('Implement setPriceToPay() method');
    }
}
