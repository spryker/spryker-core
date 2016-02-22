<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Calculation\Dependency\Transfer;

interface TaxableItemInterface
{

    /**
     * @return int
     */
    public function getTaxPercentage();

    /**
     * @param int $taxPercentage
     *
     * @return $this
     */
    public function setTaxPercentage($taxPercentage);

    /**
     * @return int
     */
    public function getPriceToPay();

    /**
     * @param int $price
     *
     * @return $this
     */
    public function setPriceToPay($price);

}
