<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Dependency\Plugin;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductDecisionPluginInterface
{
    /**
     * Specification:
     *  - Price decision plugins receive price product collection and should make decision about if it can use any of provided prices,
     *    if it finds one then returns MoneyValueTransfer otherwise null.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchValue(array $priceProductTransferCollection, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?MoneyValueTransfer;
}
