<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface;

/**
 * @method \Spryker\Zed\MerchantCommission\MerchantCommissionConfig getConfig()
 * @method \Spryker\Zed\MerchantCommission\Business\MerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantCommission\Communication\MerchantCommissionCommunicationFactory getFactory()
 */
class PercentageMerchantCommissionCalculatorPlugin extends AbstractPlugin implements MerchantCommissionCalculatorPluginInterface
{
    /**
     * @var string
     */
    protected const CALCULATOR_TYPE = 'percentage';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getCalculatorType(): string
    {
        return static::CALCULATOR_TYPE;
    }

    /**
     * {@inheritDoc}
     * - Requires `MerchantCommissionCalculationRequestTransfer.store` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.store.name` to be set.
     * - Requires `MerchantCommissionCalculationRequestItemTransfer.sumNetPrice` to be set.
     * - Requires `MerchantCommissionCalculationRequestItemTransfer.sumGrossPrice` to be set.
     * - Requires `MerchantCommissionTransfer.amount` to be set.
     * - Calculates merchant commission amount for provided item.
     * - Rounds cent fraction for total merchant commission amount.
     * - Uses {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::getPercentageMerchantCommissionCalculationRoundMode()} to get the rounding config.
     * - Returns calculated merchant commission amount for configured price mode.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantCommissionTransfer $merchantCommissionTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer
     * @param \Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
     *
     * @return int
     */
    public function calculateMerchantCommission(
        MerchantCommissionTransfer $merchantCommissionTransfer,
        MerchantCommissionCalculationRequestItemTransfer $merchantCommissionCalculationRequestItemTransfer,
        MerchantCommissionCalculationRequestTransfer $merchantCommissionCalculationRequestTransfer
    ): int {
        return $this->getFacade()->calculatePercentageMerchantCommissionAmount(
            $merchantCommissionTransfer,
            $merchantCommissionCalculationRequestItemTransfer,
            $merchantCommissionCalculationRequestTransfer,
        );
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param float $merchantCommissionAmount
     *
     * @return int
     */
    public function transformAmountForPersistence(float $merchantCommissionAmount): int
    {
        return (int)round($merchantCommissionAmount * 100);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $merchantCommissionAmount
     *
     * @return float
     */
    public function transformAmountFromPersistence(int $merchantCommissionAmount): float
    {
        return round($merchantCommissionAmount / 100, 2);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $merchantCommissionAmount
     * @param string|null $currencyIsoCode
     *
     * @return string
     */
    public function formatMerchantCommissionAmount(int $merchantCommissionAmount, ?string $currencyIsoCode = null): string
    {
        return sprintf('%s %%', $this->transformAmountFromPersistence($merchantCommissionAmount));
    }
}
