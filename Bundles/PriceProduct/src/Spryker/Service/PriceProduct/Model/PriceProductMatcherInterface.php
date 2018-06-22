<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct\Model;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductMatcherInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransferCollection
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return int|null
     */
    public function matchPriceValue(
        array $priceProductTransferCollection,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?int;
}
