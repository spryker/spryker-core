<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface PriceDimensionUnconditionalQueryCriteriaPluginInterface
{
    /**
     * Specification:
     *  - Returns QueryCriteriaTransfer which provides criteria filters to select all prices without conditions.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildUnconditionalPriceDimensionQueryCriteria(): QueryCriteriaTransfer;
}
