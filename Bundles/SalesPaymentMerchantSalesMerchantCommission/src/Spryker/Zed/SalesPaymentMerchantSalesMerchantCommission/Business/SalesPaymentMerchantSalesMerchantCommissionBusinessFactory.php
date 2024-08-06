<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorComposite;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorInterface;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountGrossModeCalculator;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountNetModeCalculator;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutReverseAmountModeCalculator;

/**
 * @method \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\SalesPaymentMerchantSalesMerchantCommissionConfig getConfig()
 */
class SalesPaymentMerchantSalesMerchantCommissionBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorInterface
     */
    public function createPayoutAmountCalculatorComposite(): PayoutAmountCalculatorInterface
    {
        return new PayoutAmountCalculatorComposite($this->getPayoutAmountCalculatorStrategies());
    }

    /**
     * @return array<\Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface>
     */
    public function getPayoutAmountCalculatorStrategies(): array
    {
        return [
            $this->createPayoutAmountGrossModeCalculator(),
            $this->createPayoutAmountNetModeCalculator(),
        ];
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorInterface
     */
    public function createPayoutReverseAmountCalculatorComposite(): PayoutAmountCalculatorInterface
    {
        return new PayoutAmountCalculatorComposite($this->getPayoutReverseAmountCalculatorStrategies());
    }

    /**
     * @return array<\Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface>
     */
    public function getPayoutReverseAmountCalculatorStrategies(): array
    {
        return [
            $this->createPayoutReverseAmountModeCalculator(),
        ];
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface
     */
    public function createPayoutAmountGrossModeCalculator(): PayoutAmountCalculatorStrategyInterface
    {
        return new PayoutAmountGrossModeCalculator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface
     */
    public function createPayoutAmountNetModeCalculator(): PayoutAmountCalculatorStrategyInterface
    {
        return new PayoutAmountNetModeCalculator($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Business\Calculator\PayoutAmountCalculatorStrategyInterface
     */
    public function createPayoutReverseAmountModeCalculator(): PayoutAmountCalculatorStrategyInterface
    {
        return new PayoutReverseAmountModeCalculator($this->getConfig());
    }
}
