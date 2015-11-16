<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerEngine\Shared\Transfer\TransferInterface;

interface PriceItemInterface extends TransferInterface
{

    /**
     * @return int
     */
    public function getGrossPrice();

    /**
     * @param int $grossPrice
     *
     * @return self
     */
    public function setGrossPrice($grossPrice);

    /**
     * @return int
     */
    public function getPriceToPay();

    /**
     * @param int $priceToPay
     *
     * @return self
     */
    public function setPriceToPay($priceToPay);

}
