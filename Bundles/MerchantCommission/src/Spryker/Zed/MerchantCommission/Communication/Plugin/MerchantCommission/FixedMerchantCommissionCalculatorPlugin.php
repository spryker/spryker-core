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
class FixedMerchantCommissionCalculatorPlugin extends AbstractPlugin implements MerchantCommissionCalculatorPluginInterface
{
    /**
     * @var string
     */
    protected const CALCULATOR_TYPE = 'fixed';

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
     * - Requires `MerchantCommissionCalculationRequestTransfer.currency` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.currency.code` to be set.
     * - Requires `MerchantCommissionCalculationRequestItemTransfer.quantity` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.currency` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.currency.code` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.netAmount` to be set.
     * - Requires `MerchantCommissionTransfer.merchantCommissionAmount.grossAmount` to be set.
     * - Requires `MerchantCommissionCalculationRequestTransfer.priceMode` if {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::isMerchantCommissionPriceModeForStoreCalculationEnabled()} returns `false`.
     * - Uses price mode set in `MerchantCommissionCalculationRequestTransfer.priceMode` if {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::isMerchantCommissionPriceModeForStoreCalculationEnabled()}` returns `false`,
     *   uses {@link \Spryker\Zed\MerchantCommission\MerchantCommissionConfig::getMerchantCommissionPriceModeForStore()} config otherwise.
     * - Returns calculated merchant commission amount for configured price mode.
     * - Returns 0 if merchant commission amount for configured price mode is not provided.
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
        return $this->getFacade()->calculateFixedMerchantCommissionAmount(
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
        return $this->getFactory()->getMoneyFacade()->convertDecimalToInteger($merchantCommissionAmount);
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
        return $this->getFactory()->getMoneyFacade()->convertIntegerToDecimal($merchantCommissionAmount);
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
        $moneyTransfer = $this->getFactory()->getMoneyFacade()->fromInteger($merchantCommissionAmount, $currencyIsoCode);

        return $this->getFactory()->getMoneyFacade()->formatWithSymbol($moneyTransfer);
    }
}
