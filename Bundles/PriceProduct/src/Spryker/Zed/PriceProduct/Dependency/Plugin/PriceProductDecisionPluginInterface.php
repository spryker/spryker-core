<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Dependency\Plugin;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductDecisionPluginInterface
{
    /**
     * Specification:
     *  - The decision plugins are executed when filtering customer price, first part is querying from database which is done with help of PriceDimension Expanders
     *  - Then price decision plugins receive price product collection and should make decision about if it can use any of queried prices, if it finds one then returns MoneyValueTransfer otherwise null
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer[] $priceProductStoreEntityTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function matchValue(array $priceProductStoreEntityTransferCollection, PriceProductCriteriaTransfer $priceProductCriteriaTransfer);
}
