<?php

namespace Spryker\Service\PriceProduct\FilterStrategy;


use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProduct\PriceProductConfig;

class SinglePriceProductFilterMinStrategy implements SinglePriceProductFilterStrategyInterface
{
    /**
     * @param PriceProductTransfer[] $priceProductTransfers
     * @param PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return PriceProductTransfer|null
     */
    public function findOne(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        $minPriceProductTransfer = null;

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if ($minPriceProductTransfer === null) {
                $minPriceProductTransfer = $priceProductTransfer;
            }


            if ($this->isMinGreaterThan($minPriceProductTransfer, $priceProductTransfer, $priceProductFilterTransfer->getPriceMode())) {
                $minPriceProductTransfer = $priceProductTransfer;
            }
        }

        return $minPriceProductTransfer;
    }

    /**
     * @param PriceProductTransfer $minPriceProductTransfer
     * @param PriceProductTransfer $priceProductTransfer
     * @param string $priceMode
     *
     * @return bool
     */
    protected function isMinGreaterThan(PriceProductTransfer $minPriceProductTransfer, PriceProductTransfer $priceProductTransfer, string $priceMode)
    {
        if ($priceMode === PriceProductConfig::PRICE_GROSS_MODE) {
            if ($minPriceProductTransfer->getMoneyValue()->getGrossAmount() > $priceProductTransfer->getMoneyValue()->getGrossAmount()) {
                return true;
            }

            return false;
        }

        if ($minPriceProductTransfer->getMoneyValue()->getNetAmount() > $priceProductTransfer->getMoneyValue()->getNetAmount()) {
            return true;
        }

        return false;
    }

}