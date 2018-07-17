<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface MinimumOrderValueFacadeInterface
{
    /**
     * Specification:
     * - Add minimum order value strategies to the types persistence.
     *
     * @api
     *
     * @return void
     */
    public function installMinimumOrderValueTypes(): void;

    /**
     * Specification:
     * - Set store minimum order value threshold.
     *
     * @api
     *
     * @param string $strategyKey
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        string $strategyKey,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): MinimumOrderValueTransfer;

    /**
     * Specification:
     * - Get minimum order value trategy for a given key.
     *
     * @api
     *
     * @param string $strategyKey
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function getMinimumOrderValueType(
        string $strategyKey
    ): MinimumOrderValueTypeTransfer;

    /**
     * Specification:
     * - Validate if values of fee and threshold is valid for the given strategy.
     *
     * @api
     *
     * @param string $strategyKey
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return bool
     */
    public function validateStrategy(
        string $strategyKey,
        int $thresholdValue,
        ?int $fee = null
    ): bool;
}
