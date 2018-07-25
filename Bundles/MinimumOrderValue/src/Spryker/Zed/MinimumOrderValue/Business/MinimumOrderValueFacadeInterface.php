<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTypeTransfer;

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
     * @param \Generated\Shared\Transfer\MinimumOrderValueTransfer $minimumOrderValueTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreThreshold(
        MinimumOrderValueTransfer $minimumOrderValueTransfer
    ): MinimumOrderValueTransfer;

    /**
     * Specification:
     * - Get minimum order value trategy for a given key.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function getMinimumOrderValueType(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
    ): MinimumOrderValueTypeTransfer;

    /**
     * Specification:
     * - Validate if values of fee and threshold is valid for the given strategy.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer
     * @param int $thresholdValue
     * @param int|null $fee
     *
     * @throws \Spryker\Zed\MinimumOrderValue\Business\Strategies\Exception\StrategyNotFoundException
     *
     * @return bool
     */
    public function isStrategyValid(
        MinimumOrderValueTypeTransfer $minimumOrderValueTypeTransfer,
        int $thresholdValue,
        ?int $fee = null
    ): bool;
}
