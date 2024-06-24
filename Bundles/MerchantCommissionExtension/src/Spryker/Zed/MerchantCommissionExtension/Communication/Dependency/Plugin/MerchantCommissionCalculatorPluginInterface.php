<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin;

use Generated\Shared\Transfer\MerchantCommissionCalculationRequestItemTransfer;
use Generated\Shared\Transfer\MerchantCommissionCalculationRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionTransfer;

/**
 * Implement this interface to create a merchant commission calculator plugin.
 */
interface MerchantCommissionCalculatorPluginInterface
{
    /**
     * Specification:
     * - Returns calculator type name.
     *
     * @api
     *
     * @return string
     */
    public function getCalculatorType(): string;

    /**
     * Specification:
     * - Calculates merchant commission amount based on the provided merchant commission.
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
    ): int;

    /**
     * Specification:
     * - Transforms merchant commission amount to integer for persistence.
     *
     * @api
     *
     * @param float $merchantCommissionAmount
     *
     * @return int
     */
    public function transformAmountForPersistence(float $merchantCommissionAmount): int;

    /**
     * Specification:
     * - Transforms persisted merchant commission amount to float.
     *
     * @api
     *
     * @param int $merchantCommissionAmount
     *
     * @return float
     */
    public function transformAmountFromPersistence(int $merchantCommissionAmount): float;

    /**
     * Specification:
     * - Formats merchant commission amount to view format.
     *
     * @api
     *
     * @param int $merchantCommissionAmount
     * @param string|null $currencyIsoCode
     *
     * @return string
     */
    public function formatMerchantCommissionAmount(int $merchantCommissionAmount, ?string $currencyIsoCode = null): string;
}
