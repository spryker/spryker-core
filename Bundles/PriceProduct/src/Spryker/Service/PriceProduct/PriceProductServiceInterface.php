<?php

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface PriceProductServiceInterface
{
    /**
     * @param PriceProductTransfer[] $priceProductTransferCollection
     * @param PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MoneyValueTransfer|null
     */
    public function resolveProductPrice(
        array $priceProductTransferCollection,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): ?MoneyValueTransfer;
}
