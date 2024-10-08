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

class FixedMerchantCommissionCalculatorType implements MerchantCommissionCalculatorTypeInterface
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
        $merchantCommissionAmount = $this->getMerchantCommissionAmount(
            $merchantCommissionTransfer,
            $merchantCommissionCalculationRequestTransfer,
        );

        return $merchantCommissionAmount * $merchantCommissionCalculationRequestItemTransfer->getQuantityOrFail();
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return int
     */
    protected function getMerchantCommissionAmount(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): int {
        if ($this->merchantCommissionConfig->isMerchantCommissionPriceModeForStoreCalculationEnabled()) {
            return $this->getMerchantCommissionAmountForPriceMode(
                $merchantCommissionTransfer,
                $merchantCommissionCalculationRequestTransfer,
                $this->merchantCommissionConfig->getMerchantCommissionPriceModeForStore(
                    $merchantCommissionCalculationRequestTransfer->getStoreOrFail()->getNameOrFail(),
                ),
            );
        }

        return $this->getMerchantCommissionAmountForPriceMode(
            $merchantCommissionTransfer,
            $merchantCommissionCalculationRequestTransfer,
            $merchantCommissionCalculationRequestTransfer->getPriceModeOrFail(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     * @param string $priceMode
     *
     * @return int
     */
    protected function getMerchantCommissionAmountForPriceMode(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer,
        string $priceMode
    ): int {
        $currencyCode = $merchantCommissionCalculationRequestTransfer->getCurrencyOrFail()->getCodeOrFail();

        foreach ($merchantCommissionTransfer->getMerchantCommissionAmounts() as $merchantCommissionAmountTransfer) {
            if ($merchantCommissionAmountTransfer->getCurrencyOrFail()->getCodeOrFail() !== $currencyCode) {
                continue;
            }

            if ($priceMode === static::PRICE_MODE_NET) {
                return $merchantCommissionAmountTransfer->getNetAmount() ?? 0;
            }

            if ($priceMode === static::PRICE_MODE_GROSS) {
                return $merchantCommissionAmountTransfer->getGrossAmount() ?? 0;
            }
        }

        return 0;
    }
}
