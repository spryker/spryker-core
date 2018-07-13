<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MinimumOrderValue\Business;

use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\MinimumOrderValueTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface;

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
     * - Set store Hard minimum order value threshold.
     *
     * @api
     *
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreHardThreshold(
        MinimumOrderValueStrategyInterface $minimumOrderValueStrategy,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $value,
        ?int $fee = null
    ): MinimumOrderValueTransfer;

    /**
     * Specification:
     * - Set store Soft minimum order value threshold.
     *
     * @api
     *
     * @param \Spryker\Zed\MinimumOrderValue\Business\Strategies\MinimumOrderValueStrategyInterface $minimumOrderValueStrategy
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     * @param int $value
     * @param int|null $fee
     *
     * @return \Generated\Shared\Transfer\MinimumOrderValueTransfer
     */
    public function setStoreSoftThreshold(
        MinimumOrderValueStrategyInterface $minimumOrderValueStrategy,
        StoreTransfer $storeTransfer,
        CurrencyTransfer $currencyTransfer,
        int $value,
        ?int $fee = null
    ): MinimumOrderValueTransfer;
}
