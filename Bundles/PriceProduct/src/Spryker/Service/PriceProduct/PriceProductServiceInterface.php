<?php

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductServiceInterface
{
    /**
     * @param array $priceProductTransferCollection
     * @param PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return CurrentProductPriceTransfer
     */
    public function resolveProductPrice(
        array $priceProductTransferCollection,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): CurrentProductPriceTransfer;
}
