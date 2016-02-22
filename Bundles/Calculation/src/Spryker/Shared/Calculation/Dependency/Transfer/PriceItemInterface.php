<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Calculation\Dependency\Transfer;

use Spryker\Shared\Transfer\TransferInterface;

interface PriceItemInterface extends TransferInterface
{

    /**
     * @return int
     */
    public function getGrossPrice();

    /**
     * @param int $grossPrice
     *
     * @return $this
     */
    public function setGrossPrice($grossPrice);

    /**
     * @return int
     */
    public function getPriceToPay();

    /**
     * @param int $priceToPay
     *
     * @return $this
     */
    public function setPriceToPay($priceToPay);

}
