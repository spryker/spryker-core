<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProductExtension\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductDecisionPluginInterface
{
    /**
     * Specification:
     *  - Price decision plugins receive price product collection and should make decision about if it can use any of provided prices,
     *    if it finds one then returns MoneyValueTransfer otherwise null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByPriceProductCriteria(array $priceProductTransfers, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer;

    /**
     * Specification:
     *  - Price decision plugins receive price product collection and should make decision about if it can use any of provided prices,
     *    if it finds one then returns MoneyValueTransfer otherwise null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function matchPriceByPriceProductFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer;

    /**
     * Specification:
     *  - Returns dimension name.
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string;
}
