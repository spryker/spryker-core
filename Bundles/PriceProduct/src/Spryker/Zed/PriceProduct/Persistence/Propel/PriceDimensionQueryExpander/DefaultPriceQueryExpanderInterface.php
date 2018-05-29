<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence\Propel\PriceDimensionQueryExpander;

use Generated\Shared\Transfer\PriceDimensionCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface DefaultPriceQueryExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceDimensionCriteriaTransfer|null
     */
    public function buildDefaultPriceDimensionCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceDimensionCriteriaTransfer;
}
