<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;

interface PriceDimensionQueryCriteriaPluginInterface
{
    /**
     * Specification:
     *  - Builds an expander for default price criteria when querying prices from database,
     *    it could contain joins, selected columns, conditions for later filtering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildPriceDimensionQueryCriteria(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?QueryCriteriaTransfer;

    /**
     * Specification:
     *  - Builds an unconditional expander for default price criteria when querying prices from database,
     *    it could contain joins, selected columns, conditions for later filtering.
     *  - This method should always return QueryCriteriaTransfer with join and columns to select all prices without conditions.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildUnconditionalPriceDimensionQueryCriteria(): QueryCriteriaTransfer;

    /**
     * Specification:
     *   - Returns dimension name.
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string;
}
