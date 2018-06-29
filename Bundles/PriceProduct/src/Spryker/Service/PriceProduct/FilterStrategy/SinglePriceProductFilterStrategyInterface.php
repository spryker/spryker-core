<?php

namespace Spryker\Service\PriceProduct\FilterStrategy;


use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;

interface SinglePriceProductFilterStrategyInterface
{
    /**
     * @param PriceProductTransfer[] $priceProductTransfers
     * @param PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return PriceProductTransfer|null
     */
    public function findOne(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer;
}