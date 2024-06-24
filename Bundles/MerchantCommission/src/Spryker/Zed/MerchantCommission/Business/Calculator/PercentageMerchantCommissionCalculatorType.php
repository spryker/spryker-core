<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Calculator;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\MerchantCommission\MerchantCommissionConfig;

class PercentageMerchantCommissionCalculatorType implements MerchantCommissionCalculatorTypeInterface
{
    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_NET
     *
     * @var string
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @var \Spryker\Zed\MerchantCommission\MerchantCommissionConfig
     */
    protected MerchantCommissionConfig $merchantCommissionConfig;

    /**
     * @param \Spryker\Zed\MerchantCommission\MerchantCommissionConfig $merchantCommissionConfig
     */
    public function __construct(MerchantCommissionConfig $merchantCommissionConfig)
    {
        $this->merchantCommissionConfig = $merchantCommissionConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return int
     */
    public function calculateMerchantCommissionAmount(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): int {
        $merchantCommissionPercent = $merchantCommissionTransfer->getAmountOrFail() / 100;
        if ($merchantCommissionPercent <= 0) {
            return 0;
        }

        $priceMode = $this->merchantCommissionConfig->getMerchantCommissionPriceModeForStore(
            $merchantCommissionCalculationRequestTransfer->getStoreOrFail()->getNameOrFail(),
        );

        if ($priceMode === static::PRICE_MODE_NET) {
            return $this->calculate(
                $merchantCommissionCalculationRequestItemTransfer->getSumNetPrice() ?? 0,
                $merchantCommissionPercent,
            );
        }

        if ($priceMode === static::PRICE_MODE_GROSS) {
            return $this->calculate(
                $merchantCommissionCalculationRequestItemTransfer->getSumGrossPrice() ?? 0,
                $merchantCommissionPercent,
            );
        }

        return 0;
    }

    /**
     * @param int $itemPrice
     * @param float $merchantCommissionPercent
     *
     * @return int
     */
    protected function calculate(int $itemPrice, float $merchantCommissionPercent): int
    {
        $calculatedMerchantCommissionAmount = $itemPrice * $merchantCommissionPercent / 100;

        return (int)round(
            $calculatedMerchantCommissionAmount,
            0,
            $this->merchantCommissionConfig->getPercentageMerchantCommissionCalculationRoundMode(),
        );
    }
}
