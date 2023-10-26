<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Aggregator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\TaxAppSaleTransfer;

interface PriceAggregatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppSaleTransfer $taxAppSaleTransfer
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function calculatePriceAggregation(
        TaxAppSaleTransfer $taxAppSaleTransfer,
        CalculableObjectTransfer $calculableObjectTransfer
    ): CalculableObjectTransfer;
}
